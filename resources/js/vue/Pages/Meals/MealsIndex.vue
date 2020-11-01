<template>
    <page>
        <div class="my-12 flex justify-between items-center">
            <p class="text-5xl font-black">Meals</p>
            <div class="flex items-center relative">
                <input
                    ref="search"
                    type="text"
                    v-model="search"
                    @keydown.escape="search = ''"
                    :placeholder="`Search from all ${meals.length} meals`"
                    class="border border-grey-300 rounded-lg p-2 w-80"
                />
                <button
                    @click="
                        search = '';
                        $refs.search.focus();
                    "
                    class="ml-2 w-6 h-6 pb-1 font-semibold rounded-full bg-gray-800 text-white leading-none flex justify-center items-center absolute right-0 mr-2"
                >
                    &times;
                </button>
            </div>

            <create-meal-button></create-meal-button>
        </div>

        <div class="my-12" v-if="!filtered">
            <div class="my-12">
                <p class="font-bold mb-6">Recent</p>
                <meal-list :meals="recent"></meal-list>
            </div>
            <div class="my-12">
                <p class="font-bold mb-6">All Meals</p>
                <meal-list :meals="meals"></meal-list>
            </div>
        </div>

        <div v-else class="my-12">
            <p class="font-bold mb-6">Matching "{{ this.search }}"</p>
            <meal-list :meals="matches"></meal-list>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import MealList from "../../Components/Meals/MealList";
import CreateMealButton from "../../Components/Meals/CreateMealButton";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        Page,
        PageHeader,
        MealList,
        CreateMealButton,
    },

    data() {
        return {
            search: "",
        };
    },

    computed: {
        meals() {
            return this.$store.state.meals.meals;
        },

        recent() {
            return this.$store.getters["meals/recent"];
        },

        filtered() {
            return this.search.length > 2;
        },

        matches() {
            if (!this.filtered) {
                return [];
            }

            return this.meals.filter((meal) =>
                meal.name.toLowerCase().includes(this.search.toLowerCase())
            );
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals").catch(showError);
    },
};
</script>
