<template>
    <page v-if="meal">
        <page-header :title="meal_title"></page-header>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";

export default {
    components: {
        Page,
        PageHeader,
    },

    computed: {
        meal() {
            return this.$store.getters["menus/byId"](this.$route.params.id);
        },

        meal_title() {
            return `Menu #${this.meal.week_number}: ${this.meal.current_range_pretty}`;
        },
    },
};
</script>
