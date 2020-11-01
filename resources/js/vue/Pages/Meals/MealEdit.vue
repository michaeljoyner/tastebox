<template>
    <page v-if="meal">
        <meal-form :meal="meal"></meal-form>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import MealForm from "../../Components/Meals/MealForm";

export default {
    components: {
        MealForm,
        Page,
    },

    computed: {
        meal() {
            return this.$store.getters["meals/byId"](this.$route.params.id);
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals");
    },
};
</script>
