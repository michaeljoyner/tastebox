<template>
    <div v-if="kit.id">
        <h1 class="text-center text-4xl my-20">Build a kit</h1>

        <div
            v-for="meal in menu.meals"
            :key="meal.id"
            class="w-5/5 max-w-3xl my-12 mx-auto shadow flex"
        >
            <div class="w-48 h-32">
                <img
                    :src="meal.title_image.thumb"
                    :alt="meal.name"
                    class="w-full h-full object-cover"
                />
            </div>
            <div class="flex-1 px-6 relative">
                <p class="font-bold">{{ meal.name }}</p>
                <p class="mt-2 text-sm">{{ meal.description }}</p>

                <manage-servings
                    :meal-id="meal.id"
                    :kit-id="kit.id"
                    :current-state="kitMealServings(meal.id)"
                    @updated="setKit"
                ></manage-servings>

                <check-icon
                    v-if="mealIsInKit(meal.id)"
                    class="text-green-500 h-5 absolute top-0 right-0 mt-1 mr-1"
                ></check-icon>
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

    props: ["menu", "initial-kit"],

    data() {
        return {
            kit: {
                id: null,
                menu_id: null,
                meals: [],
            },
        };
    },

    mounted() {
        this.kit = this.initialKit;
    },

    methods: {
        kitMealServings(meal_id) {
            const kit_meal = this.kit.meals.find((m) => m.id === meal_id);

            return kit_meal ? kit_meal.servings : 0;
        },

        setKit(updated_kit) {
            this.kit = updated_kit;
            eventHub.$emit("basket-updated");
        },

        mealIsInKit(meal_id) {
            return this.kit.meals.some((m) => m.id === meal_id);
        },
    },
};
</script>
