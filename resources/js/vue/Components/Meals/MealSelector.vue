<template>
    <div>
        <div class="my-3">
            <button
                v-for="category in classifications"
                :key="category.id"
                class="mr-3 px-3 border border-black text-xs hover:border-green-600 rounded focus:outline-none"
                :class="{
                    'bg-green-200': selected_category === category.id,
                }"
                @click="setCategory(category.id)"
            >
                {{ category.name }}
            </button>
        </div>
        <div
            class="flex items-center px-4 rounded-full shadow my-4 focus-within:ring-1"
        >
            <search-icon class="h-4 text-gray-400"></search-icon>
            <input
                type="text"
                v-model="search"
                class="form-input mt-0 focus:outline-none focus:ring-0 border-0"
                placeholder="Filter meals"
            />
        </div>
        <div class="h-100 overflow-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="p-2 text-xs text-left">Meal</th>
                        <th class="p-2 text-xs text-left">Times Used</th>
                        <th class="p-2 text-xs text-left">Kits Ordered</th>
                        <th class="p-2 text-xs text-left">Last Offered</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="meal in meal_choices"
                        :key="meal.id"
                        class="border-b border-gray-300 text-sm"
                    >
                        <td class="px-2 py-2">
                            <button
                                class="hover:text-indigo-600"
                                type="button"
                                @click="addMeal(meal)"
                            >
                                {{ meal.name }}
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            {{ meal.times_offered }}
                        </td>
                        <td class="p-2 text-center">
                            {{ meal.total_ordered }}
                        </td>
                        <td class="p-2 whitespace-nowrap text-xs">
                            <span
                                v-if="meal.upcoming"
                                class="text-xs bg-indigo-600 text-white p-1 rounded inline-block"
                            >
                                {{ meal.upcoming.replace(" from now", "") }}
                            </span>

                            <p v-else>{{ meal.last_offered_ago }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import ColourLabel from "../UI/ColourLabel.vue";
import SearchIcon from "../UI/Icons/Search.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";

const props = defineProps({ excludes: Array });
const emit = defineEmits(["selected"]);

const store = useStore();

const meals = computed(() => store.state.meals.used_meals);
const classifications = computed(() => store.state.meals.classifications);

const search = ref("");
const selected_category = ref(null);

const meal_choices = computed(() => {
    return meals.value
        .filter((meal) => !props.excludes.includes(meal.id))
        .filter((meal) =>
            meal.name.toLowerCase().includes(search.value.toLowerCase())
        )
        .filter(
            (meal) =>
                selected_category.value === null ||
                meal.classifications.some(
                    (c) => c.id === selected_category.value
                )
        );
});

const addMeal = (meal) => emit("selected", meal);

const setCategory = (category_id) => {
    if (selected_category.value === category_id) {
        return (selected_category.value = null);
    }

    selected_category.value = category_id;
};

onMounted(() => {
    store.dispatch("meals/fetchClassifications");
    store.dispatch("meals/fetchAllUsed");
});
</script>
