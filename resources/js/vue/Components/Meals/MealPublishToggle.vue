<template>
    <div class="flex justify-between items-center">
        <div class="max-w-sm" v-if="isPublic">
            <span class="font-bold px-2 py-1 rounded-lg bg-green-200"
                >Public</span
            >
            <p class="mt-4 text-sm">
                This meal can possibly be viewed by the public, and can be made
                available for purchase.
            </p>
        </div>
        <div class="max-w-sm" v-else>
            <span class="font-bold px-2 py-1 rounded-lg bg-red-200"
                >Private</span
            >
            <p class="text-sm mt-4">
                This meal can not be viewed by anyone besides admin, and can not
                be added to baskets.
            </p>
        </div>
        <waiting-button
            @click.native="toggleStatus"
            class="btn btn-second"
            :waiting="waiting"
            >{{ button_text }}</waiting-button
        >
    </div>
</template>

<script type="text/babel">
import WaitingButton from "../UI/WaitingButton";
import { showError } from "../../../libs/notifications";
export default {
    components: {
        WaitingButton,
    },

    props: ["is-public", "meal-id"],

    data() {
        return {
            waiting: false,
        };
    },

    computed: {
        button_text() {
            return this.isPublic ? "Retract" : "Publish";
        },
    },

    methods: {
        toggleStatus() {
            const action = !this.isPublic
                ? "meals/publishMeal"
                : "meals/retractMeal";
            this.waiting = true;
            this.$store
                .dispatch(action, this.mealId)
                .then(() => this.$emit("toggled"))
                .catch(showError)
                .then(() => (this.waiting = false));
        },
    },
};
</script>
