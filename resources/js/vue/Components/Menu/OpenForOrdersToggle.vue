<template>
    <div class="p-6 shadow flex justify-between items-center">
        <div>
            <div v-if="menu.can_order">
                <colour-label
                    text="Open for orders"
                    colour="green"
                ></colour-label>
                <p class="my-4 max-w-md">
                    This menu is marked as open for orders, and will be shown on
                    the site when applicable.
                </p>
            </div>
            <div v-else>
                <colour-label
                    text="closed for orders"
                    colour="orange"
                ></colour-label>
                <p class="my-4 max-w-md">
                    This menu is currently closed for orders, and will not
                    appear on the site.
                </p>
            </div>
        </div>
        <button
            :disabled="waiting"
            @click="toggleStatus"
            class="btn"
            :class="{ 'btn-second': !menu.can_order }"
        >
            {{ button_text }}
        </button>
    </div>
</template>

<script type="text/babel">
import ColourLabel from "../UI/ColourLabel.vue";
import { showError, showSuccess } from "../../../libs/notifications.js";
export default {
    components: {
        ColourLabel,
    },

    props: ["menu"],

    data() {
        return {
            waiting: false,
        };
    },

    computed: {
        button_text() {
            return this.menu.can_order ? "Mark as Closed" : "Open orders";
        },
    },

    methods: {
        toggleStatus() {
            this.menu.can_order ? this.close() : this.open();
        },

        open() {
            this.$store
                .dispatch("menus/openForOrders", this.menu.id)
                .then(() => showSuccess("Menu open for orders"))
                .catch(() => showError("Unable to open for orders"))
                .then(() => (this.waiting = false));
        },

        close() {
            this.$store
                .dispatch("menus/closeForOrders", this.menu.id)
                .then(() => showSuccess("Menu closed for orders"))
                .catch(() => showError("Unable to close for orders"))
                .then(() => (this.waiting = false));
        },
    },
};
</script>
