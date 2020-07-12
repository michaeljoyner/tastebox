<template>
    <div>
        <button
            @click="remove"
            class="text-gray-600 hover:text-red-500 hover:underline mr-4"
        >
            Remove
        </button>
        <span class="inline-flex bg-gray-800">
            <button
                @click="decServings"
                class="text-white hover:text-yellow-500 text-xl font-bold border-r border-grey-100 px-3 mr-2"
            >
                -
            </button>
            <span class="text-white text-xl font-bold">{{ servings }}</span>
            <button
                class="text-white text-xl font-bold border-l border-grey-100 px-3 ml-2"
                @click="incServings"
            >
                +
            </button>
        </span>

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded"
            @click="addToKit"
        >
            {{ set_button_text }}
        </button>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["meal-id", "current-state", "kit-id"],

    data() {
        return {
            servings: 2,
            waiting: false,
        };
    },

    computed: {
        set_button_text() {
            return this.currentState ? "Update Kit" : "Add to Kit";
        },
    },

    mounted() {
        this.servings = this.currentState ? this.currentState : 2;
    },

    methods: {
        decServings() {
            if (this.servings > 1) {
                this.servings--;
            }
        },

        incServings() {
            if (this.servings < 6) {
                this.servings++;
            }
        },

        addToKit() {
            axios
                .post(`/my-kits/${this.kitId}/meals`, {
                    meal_id: this.mealId,
                    servings: this.servings,
                })
                .then(({ data }) => this.$emit("updated", data));
        },

        remove() {
            axios
                .delete(`/my-kits/${this.kitId}/meals/${this.mealId}`)
                .then(({ data }) => this.$emit("updated", data));
        },
    },
};
</script>
