<template>
    <div>
        <div class="flex justify-between">
            <p class="text-lg font-bold">Place a Manual Order</p>
        </div>

        <div v-if="available_meals.length">
            <manual-order-form
                :available-meals="available_meals"
            ></manual-order-form>
        </div>
    </div>
</template>

<script>
import ManualOrderForm from "./ManualOrderForm";
import { showError } from "../../../libs/notifications";
export default {
    components: { ManualOrderForm },

    computed: {
        available_meals() {
            return this.$store.getters["menus/currentAvailableMeals"];
        },
    },

    mounted() {
        this.$store
            .dispatch("menus/fetchMenus")
            .catch(() => showError("failed to fetch menus"));
    },
};
</script>
