<template>
    <page v-if="meal">
        <page-header :title="meal.name">
            <router-link :to="`/meals/${meal.id}/gallery`" class="btn mx-4"
                >Pics</router-link
            >
            <router-link :to="`/meals/${meal.id}/edit`" class="btn btn-main"
                >Edit</router-link
            >
        </page-header>

        <meal-publish-toggle
            :is-public="meal.is_public"
            :meal-id="meal.id"
            @toggled="fetchMeal"
            class="p-4 shadow"
        ></meal-publish-toggle>

        <div class="flex justify-between my-12">
            <div>
                <span
                    class="px-2 py-1 rounded-lg font-bold border-2 border-black text-sm mr-4"
                    v-for="classification in meal.classifications"
                    :key="classification.id"
                >
                    {{ classification.name }}
                </span>
                <meal-times class="my-6" :meal="meal"></meal-times>
                <p class="max-w-lg text-lg">{{ meal.description }}</p>
                <p class="my-6">
                    <strong>Allergens: </strong>{{ meal.allergens }}
                </p>
            </div>

            <nutritional-info class="w-64 pt-6" :meal="meal"></nutritional-info>
        </div>

        <div>
            <p class="font-bold mb-6">Ingredients</p>
            <div class="flex justify-between">
                <div class="w-1/2 mr-6">
                    <p class="font-bold text-gray-600">Included in Kit:</p>
                    <ul>
                        <li v-for="kit_ingredient in kit_ingredients">
                            {{ kit_ingredient }}
                        </li>
                    </ul>
                </div>
                <div class="w-1/2 ml-6">
                    <p class="font-bold text-gray-600">Customer to supply</p>
                    <ul>
                        <li v-for="customer_ingredient in customer_ingredients">
                            {{ customer_ingredient }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="my-12 max-w-2xl">
            <p class="font-bold mb-6">Instructions</p>
            <div v-html="meal.instructions"></div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import NutritionalInfo from "../../Components/Meals/NutritionalInfo";
import MealTimes from "../../Components/Meals/MealTimes";
import MealPublishToggle from "../../Components/Meals/MealPublishToggle";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        Page,
        PageHeader,
        NutritionalInfo,
        MealTimes,
        MealPublishToggle,
    },

    data() {
        return {
            meal: null,
        };
    },

    computed: {
        kit_ingredients() {
            return this.meal.ingredients
                .filter((i) => i.in_kit)
                .map((i) => `${i.quantity || ""} ${i.description}`);
        },

        customer_ingredients() {
            return this.meal.ingredients
                .filter((i) => !i.in_kit)
                .map((i) => `${i.quantity || ""} ${i.description}`);
        },
    },

    mounted() {
        this.fetchMeal();
    },

    watch: {
        $route() {
            this.meal = null;
            this.fetchMeal();
        },
    },

    methods: {
        fetchMeal() {
            this.$store
                .dispatch("meals/findById", this.$route.params.id)
                .then((meal) => (this.meal = meal))
                .catch(showError);
        },
    },
};
</script>

<style lang="less">
ul {
    list-style-type: disc;
    list-style-position: inside;
}

ol {
    list-style-type: decimal;
    list-style-position: inside;
}
</style>
