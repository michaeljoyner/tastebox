<template>
    <page v-if="menu">
        <page-header :title="`Select meals for #${menu.week_number}`">
            <router-link :to="`/menus/${menu.id}`" class="mx-4 btn-muted"
                >Back to Menu</router-link
            >
            <button class="btn btn-main" @click="save">Save</button>
        </page-header>

        <div class="">
            <div
                class="max-w-lg fixed bottom-0 right-0 m-6 shadow-lg bg-white w-full"
            >
                <div
                    class="flex justify-between items-center p-2 bg-red-500 text-white"
                >
                    <p class="text-lg font-bold">
                        {{ selected_meals.length }} Selected meals
                    </p>
                    <button
                        @click="showSelected = !showSelected"
                        class="focus:outline-none"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            class="fill-current h-5 transform -rotate-45"
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
                    <search-icon
                        class="h-6 bg-gray-100 text-gray-700"
                    ></search-icon>
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
                                <th class="p-2 text-xs text-left">
                                    Times Offered
                                </th>
                                <th class="p-2 text-xs text-left">
                                    Kits Ordered
                                </th>
                                <th class="p-2 text-xs text-left">
                                    Last Offered
                                </th>
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
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import SearchIcon from "../../Components/UI/Icons/Search";
import { showError, showSuccess } from "../../../libs/notifications";
import ColourLabel from "../../Components/UI/ColourLabel";

export default {
    components: {
        ColourLabel,
        Page,
        PageHeader,
        SearchIcon,
    },

    data() {
        return {
            selected_meals: [],
            search: "",
            showSelected: false,
            selected_category: null,
        };
    },

    computed: {
        menu() {
            return this.$store.getters["menus/byId"](this.$route.params.id);
        },

        meals() {
            return this.$store.state.meals.meals;
        },

        meal_choices() {
            return this.meals
                .filter(
                    (meal) =>
                        !this.selected_meals.map((m) => m.id).includes(meal.id)
                )
                .filter((meal) =>
                    meal.name.toLowerCase().includes(this.search.toLowerCase())
                )
                .filter(
                    (meal) =>
                        this.selected_category === null ||
                        meal.classifications.some(
                            (c) => c.id === this.selected_category
                        )
                );
        },

        classifications() {
            return this.$store.state.meals.classifications;
        },
    },

    mounted() {
        this.$store.dispatch("menus/fetchMenus").catch(showError);
        this.$store.dispatch("meals/fetchMeals").catch(showError);
        this.$store.dispatch("meals/fetchClassifications");
    },

    watch: {
        menu(to) {
            if (to) {
                this.setInitialMeals();
            }
        },
    },

    methods: {
        setInitialMeals() {
            this.selected_meals = this.menu.meals.map((m) => m);
        },

        addMeal(meal) {
            this.selected_meals.push(meal);
        },

        removeMeal(meal) {
            this.selected_meals = this.selected_meals.filter(
                (m) => m.id !== meal.id
            );
        },

        save() {
            this.$store
                .dispatch("menus/saveMenuMeals", {
                    menu_id: this.menu.id,
                    meal_ids: this.selected_meals.map((m) => m.id),
                })
                .then(() => showSuccess("Meals saved!"))
                .catch(() => showError("Unable to save meals"));
        },

        setCategory(category_id) {
            if (this.selected_category === category_id) {
                return (this.selected_category = null);
            }

            this.selected_category = category_id;
        },
    },
};
</script>
