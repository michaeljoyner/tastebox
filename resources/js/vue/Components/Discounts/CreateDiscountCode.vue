<template>
    <page>
        <page-header title="Create Discount Code"></page-header>

        <div class="my-12">
            <discount-form></discount-form>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page.vue";
import PageHeader from "../PageHeader.vue";
import DiscountForm from "./DiscountForm.vue";
export default {
    components: { DiscountForm, Page, PageHeader },
};
</script>
