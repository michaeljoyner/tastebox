<template>
    <div>
        <div class="flex justify-end">
            <span class="type-b3 mr-4">{{ in_box_status }}</span>
            <button
                v-show="!show"
                class="type-b4 flex items-center hover:text-green-600"
                @click="show = true"
            >
                <span class="leading-none">{{ set_button_text }}</span>
                <svg
                    v-show="servings === 0"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    class="fill-current h-5"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
        </div>
        <div v-show="show" class="flex flex-wrap justify-end items-center my-3">
            <span class="inline-flex items-center">
                <span class="mr-4 type-b1">Cooking for: </span>
                <button
                    v-for="amount in possible_servings"
                    :key="amount"
                    :disabled="waiting || servings === amount"
                    :class="{
                        'bg-gray-400': waiting || servings === amount,
                    }"
                    @click="setServings(amount)"
                    class="w-6 h-6 ml-4 border border-black rounded flex justify-center items-center hover:bg-green-300"
                >
                    {{ amount }}
                </button>
            </span>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["meal-id", "current-state", "kit-id"],

    data() {
        return {
            possible_servings: [0, 1, 2, 4],
            show: false,
            servings: 0,
            waiting: false,
        };
    },

    computed: {
        set_button_text() {
            return this.servings > 0 ? "Change" : "Add to Box";
        },

        in_box_status() {
            if (this.servings === 0) {
                return "";
            }

            return `${this.servings} packed in your box`;
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
        setServings(amount) {
            if (amount === 0) {
                return this.remove();
            }

            this.waiting = true;
            axios
                .post(`/my-kits/${this.kitId}/meals`, {
                    meal_id: this.mealId,
                    servings: amount,
                })
                .then(({ data }) => {
                    this.$emit("updated", data);
                    this.servings = amount;
                })
                .then(() => {
                    this.waiting = false;
                    this.show = false;
                });
        },

        remove() {
            axios
                .delete(`/my-kits/${this.kitId}/meals/${this.mealId}`)
                .then(({ data }) => {
                    this.$emit("updated", data);
                    this.servings = 0;
                    this.show = false;
                });
        },
    },
};
</script>
