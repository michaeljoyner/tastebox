<template>
    <div class="max-w-5xl mx-auto">
        <page-header title="Meals">
            <create-meal-button></create-meal-button>
        </page-header>
        <div class="max-w-4xl px-6">
            <div
                v-for="meal in meals"
                :key="meal.id"
                class="bg-white shadow my-6"
            >
                <router-link
                    :to="`/meals/${meal.id}`"
                    class="text-gray-800 hover:text-pink-500 flex"
                >
                    <img :src="meal.title_image.thumb" class="w-32" />
                    <div class="p-4">
                        <p class="font-bold">{{ meal.name }}</p>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../../Components/PageHeader";
import CreateMealButton from "../../Components/Meals/CreateMealButton";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        PageHeader,
        CreateMealButton,
    },

    computed: {
        meals() {
            return this.$store.state.meals.meals;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals").catch(showError);
    },
};
</script>
