<template>
    <div>
        <p class="font-bold mb-4">Publish Status</p>
        <div class="flex justify-between items-center">
            <div class="max-w-sm" v-if="isPublic">
                <colour-label colour="green" text="public"></colour-label>
                <p class="mt-4 text-sm">
                    This meal can possibly be viewed by the public, and can be
                    made available for purchase.
                </p>
            </div>
            <div class="max-w-sm" v-else>
                <colour-label colour="orange" text="private"></colour-label>
                <p class="text-sm mt-4">
                    This meal can not be viewed by anyone besides admin, and can
                    not be added to baskets.
                </p>
            </div>
            <waiting-button
                @click="toggleStatus"
                class="btn btn-second"
                :waiting="waiting"
                >{{ button_text }}</waiting-button
            >
        </div>
    </div>
</template>

<script type="text/babel">
import WaitingButton from "../UI/WaitingButton.vue";
import { showError } from "../../../libs/notifications.js";
import ColourLabel from "../UI/ColourLabel.vue";
export default {
    components: {
        ColourLabel,
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
            const action = !this.isPublic ? "meals/publish" : "meals/retract";
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
