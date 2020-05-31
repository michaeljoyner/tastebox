<template>
    <page>
        <page-header title="Meals">
            <create-meal-button></create-meal-button>
        </page-header>
        <meal-list :meals="meals"></meal-list>
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

    computed: {
        meals() {
            return this.$store.state.meals.meals;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals").catch(showError);
    },
};
</script>
