<template>
    <div>
        <div class="my-3">
            <button
                v-for="category in classifications"
                :key="category.id"
                class="mr-4 px-3 border border-black text-sm hover:border-green-600 rounded focus:outline-none"
                :class="{
                    'bg-green-200': selected_category === category.id,
                }"
                @click="setCategory(category.id)"
            >
                {{ category.name }}
            </button>
        </div>
        <div class="flex items-center bg-gray-100 px-2">
            <search-icon class="h-6 bg-gray-100 text-gray-700"></search-icon>
            <input
                type="text"
                v-model="search"
                class="form-input mt-0 focus:outline-none"
                placeholder="Filter meals"
            />
        </div>
        <div class="h-100 overflow-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="p-2 text-xs text-left">Meal</th>
                        <th class="p-2 text-xs text-left">Times Offered</th>
                        <th class="p-2 text-xs text-left">Kits Ordered</th>
                        <th class="p-2 text-xs text-left">Last Offered</th>
                        <th class="p-2 text-xs text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="meal in meal_choices"
                        :key="meal.id"
                        class="border-b border-gray-300"
                    >
                        <td class="px-2 py-2">{{ meal.name }}</td>
                        <td class="p-2">{{ meal.times_offered }}</td>
                        <td class="p-2">{{ meal.total_ordered }}</td>
                        <td class="p-2 whitespace-nowrap text-xs">
                            <colour-label
                                v-if="meal.upcoming"
                                colour="green"
                                :text="meal.upcoming"
                                :small="true"
                            ></colour-label>
                            <p v-else>{{ meal.last_offered_ago }}</p>
                        </td>
                        <td>
                            <button
                                @click="addMeal(meal)"
                                class="font-bold text-gray-600 hover:text-blue-500 text-xs"
                            >
                                Select
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/babel">
import ColourLabel from "../UI/ColourLabel.vue";
import SearchIcon from "../UI/Icons/Search.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
export default {
    components: {
        ColourLabel,
        SearchIcon,
    },

    props: ["excludes"],
    emits: ["selected"],

    setup(props, { emit }) {
        const store = useStore();

        const meals = computed(() => store.state.meals.meals);
        const classifications = computed(
            () => store.state.meals.classifications
        );

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
            store.dispatch("meals/fetchMeals");
        });

        return {
            meals,
            classifications,
            meal_choices,
            selected_category,
            search,
            addMeal,
            setCategory,
        };
    },
};
</script>
