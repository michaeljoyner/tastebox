<template>
    <page>
        <div class="my-12 flex justify-between items-center">
            <p class="text-5xl font-black">Meals</p>
            <div class="flex items-center relative">
                <input
                    ref="search"
                    type="text"
                    v-model="search"
                    @keydown.escape="search = ''"
                    :placeholder="`Search from all ${meals.length} meals`"
                    class="border border-grey-300 rounded-lg p-2 w-80"
                />
                <button
                    @click="
                        search = '';
                        $refs.search.focus();
                    "
                    class="ml-2 w-6 h-6 pb-1 font-semibold rounded-full bg-gray-800 text-white leading-none flex justify-center items-center absolute right-0 mr-2"
                >
                    &times;
                </button>
            </div>

            <router-link to="/meals/create" class="btn btn-main"
                >New Meal</router-link
            >
        </div>

        <div class="my-12">
            <div class="flex justify-around">
                <button
                    v-for="classification in classifications"
                    :key="classification.id"
                    class="px-4 py-1 rounded-lg border text-sm"
                    @click="toggleClassification(classification.id)"
                    :class="{
                        'bg-green-200': selected_classifications.includes(
                            classification.id
                        ),
                    }"
                >
                    {{ classification.name }}
                </button>
            </div>
        </div>

        <div class="my-12" v-if="!filtered">
            <div class="my-12">
                <p class="font-bold mb-6">Recent</p>
                <meal-list :meals="recent"></meal-list>
            </div>
            <div class="my-12">
                <p class="font-bold mb-6">All Meals</p>
                <meal-list :meals="meals"></meal-list>
            </div>
        </div>

        <div v-else class="my-12">
            <p class="font-bold mb-6" v-show="search.length">
                Matching "{{ search }}"
            </p>
            <meal-list :meals="matches"></meal-list>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import MealList from "../../Components/Meals/MealList";
import CreateMealButton from "../../Components/Meals/CreateMealButton";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        Page,
        PageHeader,
        MealList,
        CreateMealButton,
    },

    data() {
        return {
            search: "",
            selected_classifications: [],
        };
    },

    computed: {
        meals() {
            return this.$store.state.meals.meals;
        },

        recent() {
            return this.$store.getters["meals/recent"];
        },

        filtered() {
            return (
                this.search.length > 2 ||
                this.selected_classifications.length > 0
            );
        },

        matches() {
            if (!this.filtered) {
                return [];
            }

            return this.meals
                .filter((meal) =>
                    meal.name.toLowerCase().includes(this.search.toLowerCase())
                )
                .filter((meal) => {
                    const meal_classifications = meal.classifications.map(
                        (c) => c.id
                    );
                    return this.selected_classifications.every((id) =>
                        meal_classifications.includes(id)
                    );
                });
        },

        classifications() {
            return this.$store.state.meals.classifications;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals").catch(showError);
        this.$store.dispatch("meals/fetchClassifications").catch(showError);
    },

    methods: {
        toggleClassification(id) {
            if (this.selected_classifications.includes(id)) {
                return (this.selected_classifications = this.selected_classifications.filter(
                    (c) => c !== id
                ));
            }

            this.selected_classifications.push(id);
        },
    },
};
</script>
