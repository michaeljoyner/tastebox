<template>
    <page v-if="code">
        <page-header title="Edit Discount Code">
            <router-link to="/discount-codes" class="btn mr-4"
                >Back
            </router-link>
            <delete-confirmation
                :disabled="waiting_on_delete"
                item="this discount code"
                @confirmed="deleteCode"
            ></delete-confirmation>
        </page-header>

        <div class="my-12">
            <discount-form :code="code"></discount-form>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page.vue";
import PageHeader from "../PageHeader.vue";
import DiscountForm from "./DiscountForm.vue";
import DeleteConfirmation from "../UI/DeleteConfirmation.vue";
import { showError, showSuccess } from "../../../libs/notifications.js";

export default {
    components: {
        DeleteConfirmation,
        DiscountForm,
        Page,
        PageHeader,
    },

    data() {
        return {
            waiting_on_delete: false,
        };
    },

    computed: {
        code() {
            return this.$store.getters["discounts/byId"](
                this.$route.params.code
            );
        },
    },

    mounted() {
        this.$store.dispatch("discounts/fetch");
    },

    methods: {
        deleteCode() {
            this.waiting_on_delete = true;

            const action = this.code.is_member_discount
                ? "members/deleteGeneralDiscount"
                : "discounts/delete";
            this.$store
                .dispatch(
                    action,
                    this.code.is_member_discount
                        ? this.code.discount_tag
                        : this.code.id
                )
                .then(() => {
                    showSuccess("Code deleted.");
                    this.$router.push("/discount-codes");
                })
                .catch(() => showError("Failed to delete code."))
                .then(() => (this.waiting_on_delete = false));
        },
    },
};
</script>
