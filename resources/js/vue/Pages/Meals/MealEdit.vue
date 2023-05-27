<template>
    <page v-if="meal">
        <meal-form :meal="meal"></meal-form>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import MealForm from "../../Components/Meals/MealInfoForm.vue";

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
