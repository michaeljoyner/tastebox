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
                <meal-selector
                    :excludes="selected_meals.map((m) => m.id)"
                    @selected="addMeal"
                ></meal-selector>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import SearchIcon from "../../Components/UI/Icons/Search.vue";
import { showError, showSuccess } from "../../../libs/notifications.js";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import MealSelector from "../../Components/Meals/MealSelector.vue";

export default {
    components: {
        ColourLabel,
        Page,
        PageHeader,
        SearchIcon,
        MealSelector,
    },

    data() {
        return {
            selected_meals: [],
            showSelected: false,
        };
    },

    computed: {
        menu() {
            return this.$store.getters["menus/byId"](this.$route.params.id);
        },
    },

    mounted() {
        this.$store.dispatch("menus/fetchMenus").catch(showError);
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
