<template>
    <div>
        <div class="flex justify-end">
            <span class="type-b3 mr-4">{{ in_box_status }}</span>
            <button
                v-show="!show && (canAddToBox || canChange)"
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
            <div v-show="canNotAdd" class="flex items-center">
                <p class="type-b3 text-gray-600 mr-1">Your box is full</p>
                <button
                    @click="$emit('explain-limit')"
                    class="focus:outline-none"
                >
                    <svg
                        class="h-5 text-green-600 hover:text-green-400"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
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
    props: ["meal-id", "current-state", "kit-id", "can-add-to-box"],

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

            return `${this.servings} servings packed in your box`;
        },

        unchanged() {
            return (
                this.currentState === this.servings ||
                (!this.currentState && !this.servings)
            );
        },

        canChange() {
            return this.servings > 0;
        },

        canNotAdd() {
            return !this.canAddToBox && this.servings === 0;
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
                    try {
                        fbq("track", "AddToCart");
                    } catch (e) {}
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
