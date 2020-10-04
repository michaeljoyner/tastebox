<template>
    <div v-if="kit">
        <h1 class="text-5xl font-bold text-center my-8">Build a box</h1>

        <p class="max-w-xl mx-auto px-6 mb-6">
            Select the meals you want <strong>for the week</strong> below. Our
            boxes are designed to be <strong>fresh for only one week</strong>,
            so we strongly suggest you don't over order.
        </p>

        <div class="px-6">
            <div
                v-for="meal in menu.meals"
                :key="meal.id"
                class="w-5/5 max-w-3xl my-12 mx-auto shadow relative"
            >
                <div class="flex flex-col md:flex-row border-b border-gray-200">
                    <div class="w-full h-auto md:w-64 md:h-42">
                        <img
                            :src="meal.title_image.thumb"
                            :alt="meal.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div class="flex-1 p-3">
                        <p class="font-bold">{{ meal.name }}</p>
                        <p class="mt-2 text-sm">{{ meal.description }}</p>

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
                    ></manage-servings>
                </div>
            </div>
        </div>

        <div class="px-6 my-8 max-w-xl mx-auto">
            <p>
                All done? We hope you found something to your liking. You may
                either go back and build another kit, or head on to review your
                basket and checkout.
            </p>
            <div class="flex justify-between md:justify-center mt-6">
                <button
                    class="font-bold text-green-600 hover:text-green-500 md:mr-6"
                    @click="$emit('done')"
                >
                    Build another box
                </button>

                <a
                    href="/basket"
                    class="px-4 py-2 text-white bg-green-600 hover:bg-green-500 shadow rounded-lg md:ml-6"
                    >Go to basket</a
                >
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import ManageServings from "./ManageServings";
import CheckIcon from "../UI/Icons/CheckIcon";

export default {
    components: {
        ManageServings,
        CheckIcon,
    },

    props: ["menu", "kit"],

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
