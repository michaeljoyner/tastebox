<template>
    <div>
        <div class="flex justify-between items-center">
            <p class="text-gray-500 my-4">Choose meals and how many servings</p>
            <button
                :disabled="!validEntries.length"
                class="bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-md px-4 py-1"
                @click="emitMeals"
            >
                Create List
            </button>
        </div>

        <div class="max-w-md">
            <div v-for="(meal, index) in meals" :key="meal.key">
                <MealSelectInput
                    v-model="meals[index]"
                    :meal-list="available_meals"
                    @delete="deleteMeal"
                />
            </div>

            <div class="flex justify-between">
                <button
                    class="text-xs text-gray-500 hover:text-pink-500"
                    @click="clearList"
                >
                    Clear List
                </button>
                <button
                    class="text-xs bg-indigo-500 text-white rounded px-2 py-1 font-semibold"
                    @click="addMeal"
                >
                    Add Meal
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useStore } from "vuex";
import { onMounted, computed, ref } from "vue";
import MealSelectInput from "./MealSelectInput.vue";
import { httpAction } from "../../../libs/httpAction";
import { createMealShoppingList } from "../../../apis/meals";
import { showError } from "../../../libs/notifications";
import WaitingButton from "../UI/WaitingButton.vue";

const emit = defineEmits(["selectionComplete"]);

const store = useStore();

const available_meals = computed(() => store.state.meals.used_meals);

const meals = ref([{ meal: null, qty: 1, key: crypto.randomUUID() }]);

const addMeal = () => {
    meals.value.push({ meal: null, qty: 1, key: crypto.randomUUID() });
};

const deleteMeal = (key) => {
    {
        meals.value = meals.value.filter((m) => m.key !== key);
    }
};

const clearList = () =>
    (meals.value = [{ meal: null, qty: 1, key: crypto.randomUUID() }]);

const validEntries = computed(() => meals.value.filter((m) => m.meal && m.qty));

const emitMeals = () =>
    emit(
        "selectionComplete",
        validEntries.value.map((m) => ({ meal_id: m.meal, qty: m.qty }))
    );

onMounted(() => {
    store.dispatch("meals/fetchAllUsed");
});
</script>
