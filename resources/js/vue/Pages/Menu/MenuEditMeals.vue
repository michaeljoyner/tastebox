<template>
    <page v-if="menu">
        <page-header :title="`Select meals for #${menu.week_number}`">
            <router-link :to="`/menus/${menu.id}`" class="mx-4 btn-muted"
                >Back to Menu</router-link
            >
            <button class="btn btn-main" @click="save">Save</button>
        </page-header>

        <div class="flex justify-between">
            <div class="flex-1 mr-6">
                <p class="text-lg font-bold">Selected meals</p>

                <p class="my-6 text-sm">
                    These are the currently selected meals
                </p>

                <div class="divide-y divide-gray-200">
                    <div
                        v-for="meal in selected_meals"
                        :key="meal.id"
                        class="flex justify-between my-2 pt-2 my-2"
                    >
                        <p class="flex-1 mr-4">{{ meal.name }}</p>
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
                <div class="divide-y divide-gray-200 h-100 overflow-auto">
                    <div
                        v-for="meal in meal_choices"
                        :key="meal.id"
                        class="flex justify-between my-2 pt-2"
                    >
                        <p class="flex-1 mr-4">{{ meal.name }}</p>
                        <button
                            @click="addMeal(meal)"
                            class="font-bold text-gray-600 hover:text-blue-500 text-xs"
                        >
                            Select
                        </button>
                    </div>
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

export default {
    components: {
        Page,
        PageHeader,
        SearchIcon,
    },

    data() {
        return {
            selected_meals: [],
            search: "",
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
                );
        },
    },

    mounted() {
        this.$store.dispatch("menus/fetchMenus").catch(showError);
        this.$store.dispatch("meals/fetchMeals").catch(showError);
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
    },
};
</script>
