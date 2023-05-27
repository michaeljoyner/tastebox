<template>
    <page>
        <page-header title="Create a new Meal">
            <router-link to="/meals" class="btn">Back</router-link>
        </page-header>

        <meal-info-form></meal-info-form>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page.vue";
import PageHeader from "../PageHeader.vue";
import MealInfoForm from "./MealInfoForm.vue";
export default {
    components: { MealInfoForm, Page, PageHeader },
};
</script>
