<template>
    <page>
        <page-header title="Discount Codes"></page-header>

        <div class="my-12">
            <div v-for="code in codes" :key="code.id">
                <p class="text-xl font-bold">{{ code.code }}</p>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../PageHeader";
export default {
    components: { Page, PageHeader },

    computed: {
        codes() {
            return this.$store.state.discounts.all;
        },
    },

    mounted() {
        this.$store.dispatch("discounts/fetch");
    },
};
</script>
