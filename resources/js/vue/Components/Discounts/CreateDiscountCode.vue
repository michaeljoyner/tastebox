<template>
    <page>
        <page-header title="Create Discount Code"></page-header>

        <div class="my-12">
            <discount-form></discount-form>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../PageHeader";
import DiscountForm from "./DiscountForm";
export default {
    components: { DiscountForm, Page, PageHeader },
};
</script>
