<template>
    <div v-if="kit">
        <h1 class="type-h1 font-bold text-center my-8">Build a Box</h1>

        <p class="max-w-xl mx-auto px-6 mb-6">
            Select the meals you want
            <span class="font-bold">for the week</span> below as well as the
            number of people you will be cooking for. There is a
            <span class="font-bold">minimum of 3 meals per box</span> and we
            suggest not over-ordering as the meals are designed to be
            <span class="font-bold">fresh for 5 days after delivery</span>.
        </p>

        <div class="px-6">
            <div
                v-for="meal in menu.meals"
                :key="meal.id"
                class="w-5/5 max-w-3xl my-12 mx-auto shadow md:shadow-lg bg-white relative"
            >
                <div class="flex flex-col md:flex-row border-b border-gray-200">
                    <div class="w-full h-auto md:w-80">
                        <img
                            :src="meal.title_image"
                            :alt="meal.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div class="flex-1 p-3 pl-6">
                        <p class="type-h3">{{ meal.name }}</p>

                        <div class="flex flex-wrap my-2">
                            <p
                                v-for="category in meal.classifications"
                                :key="category.id"
                                class="mr-3 border rounded border-black px-2 type-b3 mb-2 whitespace-nowrap"
                            >
                                {{ category.name }}
                            </p>
                        </div>

                        <p class="mt-2 type-b3">{{ meal.description }}</p>

                        <p class="type-b4 my-2 flex items-center leading-none">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="fill-current text-green-400 h-4 mr-2"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M16.32 7.1A8 8 0 1 1 9 4.06V2h2v2.06c1.46.18 2.8.76 3.9 1.62l1.46-1.46 1.42 1.42-1.46 1.45zM10 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12zM7 0h6v2H7V0zm5.12 8.46l1.42 1.42L10 13.4 8.59 12l3.53-3.54z"
                                />
                            </svg>

                            <span class="type-b4 pt-1"
                                >{{ meal.cook_time }} mins</span
                            >
                        </p>

                        <check-icon
                            v-if="mealIsInKit(meal.id)"
                            class="text-green-500 h-6 absolute top-0 right-0 mt-1 mr-1 bg-opaque rounded-full"
                        ></check-icon>
                    </div>
                </div>
                <div class="p-4">
                    <manage-servings
                        :meal-id="meal.id"
                        :kit-id="kit.id"
                        :current-state="kitMealServings(meal.id)"
                        @updated="setKit"
                        :can-add-to-box="kit.meals.length < 5"
                        @explain-limit="showLimitModal = true"
                    ></manage-servings>
                </div>
            </div>
        </div>

        <div class="px-6 my-8 max-w-xl mx-auto">
            <p>
                All done? We hope you’re excited to learn a few new recipes. You
                can still go back to build another box, or proceed to review
                your basket and checkout.
            </p>
            <div class="flex justify-between md:justify-center mt-6">
                <button
                    class="font-bold text-green-600 hover:text-green-500 md:mr-6"
                    @click="$emit('done')"
                >
                    Build another box
                </button>

                <a href="/basket" class="green-btn md:ml-6">Go to basket</a>
            </div>
        </div>
        <modal :show="showLimitModal" @close="showLimitModal = false">
            <div class="p-6 w-full max-w-md mx-auto bg-white">
                <p class="type-h3 text-green-700 mb-6">Your box is full!</p>
                <p class="type-b3">
                    A box may only contain up to 5 meals. If you really need
                    more, you could separate your order over two boxes. The cost
                    would be the same.
                </p>
                <p class="type-b3 mt-4">
                    Please bear in mind that our meals are only designed to stay
                    fresh until the Friday after delivery.
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        @click="showLimitModal = false"
                        class=""
                    >
                        Okay
                    </button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">
import ManageServings from "./ManageServings";
import CheckIcon from "../UI/Icons/CheckIcon";
import Modal from "../Modal";
import { eventHub } from "../../../libs/eventHub";

export default {
    components: {
        ManageServings,
        CheckIcon,
        Modal,
    },

    props: ["menu", "kit"],

    data() {
        return {
            showLimitModal: false,
        };
    },

    methods: {
        kitMealServings(meal_id) {
            const kit_meal = this.kit.meals.find((m) => m.id === meal_id);

            return kit_meal ? kit_meal.servings : 0;
        },

        setKit(updated_kit) {
            eventHub.$emit("basket-updated");
            this.$emit("kit-updated", updated_kit);
        },

        mealIsInKit(meal_id) {
            return this.kit.meals.some((m) => m.id === meal_id);
        },
    },
};
</script>
