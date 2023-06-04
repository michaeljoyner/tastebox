<template>
    <page v-if="menu">
        <page-header :title="`Select meals for #${menu.week_number}`">
            <router-link :to="`/menus/${menu.id}`" class="mx-4 muted-text-btn"
                >Back to Menu</router-link
            >
            <button class="btn btn-main" @click="save">Save</button>
        </page-header>

        <div class="">
            <div
                class="max-w-lg fixed bottom-0 right-0 m-6 shadow-lg bg-white w-full"
                :class="{ 'rounded-full overflow-hidden': !showSelected }"
            >
                <div
                    class="flex justify-between items-center p-2 bg-indigo-50 text-black hover:bg-indigo-200"
                    @click="showSelected = !showSelected"
                >
                    <p class="px-2 font-bold">
                        {{ selected_meals.length }} Selected meals
                    </p>
                    <button
                        @click="showSelected = !showSelected"
                        class="focus:outline-none"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            class="fill-current h-5 transform -rotate-45 text-slate-900"
                        >
                            <path
                                d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"
                            />
                        </svg>
                    </button>
                </div>

                <div class="divide-y divide-gray-200 p-2" v-show="showSelected">
                    <div
                        v-for="meal in selected_meals"
                        :key="meal.id"
                        class="flex justify-between my-2 pt-2 my-2"
                    >
                        <p class="flex-1 mr-4 text-sm">{{ meal.name }}</p>
                        <button
                            @click="removeMeal(meal)"
                            class="text-xs font-bold text-gray-600 hover:text-red-500"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex-1 ml-6">
                <p class="text-lg font-bold">Available Meals</p>
                <meal-selector
                    :excludes="selected_meals.map((m) => m.id)"
                    @selected="addMeal"
                ></meal-selector>
            </div>
        </div>
    </page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import SearchIcon from "../../Components/UI/Icons/Search.vue";
import { showError, showSuccess } from "../../../libs/notifications.js";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import MealSelector from "../../Components/Meals/MealSelector.vue";
import { ref, computed, onMounted, watch } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();

const selected_meals = ref([]);
const selected_category = ref(null);
const showSelected = ref(false);
const menu = computed(() => store.getters["menus/byId"](route.params.id));

const setInitialMeals = () => {
    selected_meals.value = menu.value.meals.map((m) => m);
};

watch(
    () => menu.value,
    (menu) => {
        if (menu) {
            setInitialMeals();
        }
    }
);

const addMeal = (meal) => {
    selected_meals.value.push(meal);
};

const removeMeal = (meal) => {
    selected_meals.value = selected_meals.value.filter((m) => m.id !== meal.id);
};

const save = () => {
    store
        .dispatch("menus/saveMenuMeals", {
            menu_id: menu.value.id,
            meal_ids: selected_meals.value.map((m) => m.id),
        })
        .then(() => showSuccess("Meals saved!"))
        .catch(() => showError("Unable to save meals"));
};

const setCategory = (category_id) => {
    if (selected_category.value === category_id) {
        return (selected_category.value = null);
    }

    selected_category.value = category_id;
};

onMounted(() => {
    store.dispatch("menus/fetchMenus").catch(showError);
});
</script>
