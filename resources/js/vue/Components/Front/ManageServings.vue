<template>
    <div>
        <div class="flex flex-wrap justify-end items-center my-3">
            <span class="inline-flex items-center">
                <span class="mr-4">Servings: </span>
                <button
                    @click="servings = 1"
                    class="block h-10 w-10 rounded-full shadow font-bold border-2 border-gray-200 focus:outline-none"
                    :class="{
                        'bg-green-600 text-white': currentState === 1,
                        'bg-gray-200 focus:border-green-600':
                            currentState !== 1,
                    }"
                >
                    1
                </button>
                <button
                    @click="servings = 2"
                    class="block h-10 w-10 rounded-full shadow font-bold mx-3 border-2 border-gray-200 focus:outline-none"
                    :class="{
                        'bg-green-600 text-white': currentState === 2,
                        'bg-gray-200 focus:border-green-600':
                            currentState !== 2,
                    }"
                >
                    2
                </button>
                <button
                    class="block h-10 w-10 rounded-full shadow font-bold border-2 border-gray-200 focus:outline-none"
                    :class="{
                        'bg-green-600 text-white': currentState === 4,
                        'bg-gray-200 focus:border-green-600':
                            currentState !== 4,
                    }"
                    @click="servings = 4"
                >
                    4
                </button>
            </span>

            <div class="flex items-center mt-3 md:mt-0 md:ml-6">
                <button
                    class="px-2 py-1 rounded mr-4 order-1 md:order-2"
                    :class="{
                        'text-red-600 hover:text-red-500': currentState,
                        'text-gray-500': !currentState,
                    }"
                    @click="remove"
                    :disabled="!currentState"
                >
                    Remove
                </button>

                <button
                    class="font-bold text-white px-2 py-1 rounded order-2 md:order-1"
                    :class="{
                        'bg-green-600 hover:bg-green-500': !unchanged,
                        'bg-gray-200': unchanged,
                    }"
                    @click="addToKit"
                    :disabled="unchanged"
                >
                    {{ set_button_text }}
                </button>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["meal-id", "current-state", "kit-id"],

    data() {
        return {
            servings: 0,
            waiting: false,
        };
    },

    computed: {
        set_button_text() {
            return this.currentState ? "Update Kit" : "Add to Kit";
        },

        unchanged() {
            return (
                this.currentState === this.servings ||
                (!this.currentState && !this.servings)
            );
        },
    },

    mounted() {
        this.servings = this.currentState ? this.currentState : 0;
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
                .then(({ data }) => {
                    this.$emit("updated", data);
                    this.servings = 0;
                });
        },
    },
};
</script>
