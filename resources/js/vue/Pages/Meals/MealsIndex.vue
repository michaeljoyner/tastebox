<template>
    <page>
        <div class="my-12 flex justify-between items-center">
            <p class="text-5xl font-black">Meals</p>
            <div class="flex items-center relative"></div>

            <router-link to="/meals/create" class="btn btn-main"
                >New Meal</router-link
            >
        </div>

        <MealIndexSearchBox />

        <MealsPagination />

        <div class="mt-4">
            <div>
                <SpinningIcon
                    class="w-5 h-5 text-indigo-500"
                    v-show="fetching"
                />
            </div>
            <div v-show="!fetching" class="">
                <meal-list :meals="meals"></meal-list>
            </div>
        </div>
    </page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import MealList from "../../Components/Meals/MealList.vue";
import CreateMealButton from "../../Components/Meals/CreateMealButton.vue";
import { showError } from "../../../libs/notifications.js";
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import MealIndexSearchBox from "../../Components/Meals/MealIndexSearchBox.vue";
import SpinningIcon from "../../Components/Icons/SpinningIcon.vue";
import MealsPagination from "../../Components/Meals/MealsPagination.vue";

const store = useStore();

const search = ref("");
const selected_classifications = ref([]);

const meals = computed(() => store.state.meals.meals);
const recent = computed(() => store.getters["meals/recent"]);

const classifications = computed(() => store.state.meals.classifications);
const fetching = computed(() => store.state.meals.fetching);

const toggleClassification = (id) => {
    if (selected_classifications.value.includes(id)) {
        return (selected_classifications.value =
            selected_classifications.value.filter((c) => c !== id));
    }

    selected_classifications.value.push(id);
};

onMounted(() => {
    store.dispatch("meals/fetchMeals").catch(showError);
    store.dispatch("meals/fetchClassifications").catch(showError);
});
</script>
