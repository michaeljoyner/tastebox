<template>
    <page v-if="menu">
        <PageHeader :title="`Free Recipes for #${menu.week_number}`">
            <router-link :to="`/menus/${menu.id}`" class="muted-text-btn mx-4"
                >&larr; back to menu</router-link
            >
            <SubmitButton :waiting="saving" @click="save">Save</SubmitButton>
        </PageHeader>

        <p class="my-6 text-gray-500 max-w-lg">
            Select which meals will be available as free recipes for members
            during the week of this menu.
        </p>

        <div>
            <MealSelector
                :excludes="selectedMeals.map((m) => m.id)"
                @selected="addMeal"
            ></MealSelector>
        </div>

        <div
            class="max-w-lg fixed bottom-0 right-0 m-6 shadow-lg bg-white w-full"
        >
            <div
                class="flex justify-between items-center p-2 bg-red-500 text-white"
            >
                <p class="text-lg font-bold">
                    {{ selectedMeals.length }} Selected meals
                </p>
                <button
                    @click="showSelected = !showSelected"
                    class="focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        class="fill-current h-5 transform -rotate-45"
                    >
                        <path
                            d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"
                        />
                    </svg>
                </button>
            </div>

            <div class="divide-y divide-gray-200 p-2" v-show="showSelected">
                <div
                    v-for="meal in selectedMeals"
                    :key="meal.id"
                    class="flex justify-between my-2 pt-2 my-2"
                >
                    <p class="flex-1 mr-4 text-sm">{{ meal.name }}</p>
                    <button
                        @click="removeMeal(meal)"
                        class="text-xs font-bold text-gray-600 hover:text-red-500"
                    >
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { computed, onMounted, ref, watch } from "vue";
import MealSelector from "../../Components/Meals/MealSelector.vue";
import { httpAction } from "../../../libs/httpAction.js";
import { showError, showSuccess } from "../../../libs/notifications.js";
import SubmitButton from "../../Components/UI/SubmitButton.vue";
export default {
    components: { SubmitButton, MealSelector, PageHeader, Page },

    setup(props, { emit }) {
        const store = useStore();
        const route = useRoute();

        const menu = computed(() =>
            store.getters["menus/byId"](route.params.menu)
        );

        watch(
            () => menu.value,
            (menu) => {
                if (menu) {
                    selectedMeals.value = menu.free_recipe_meals;
                }
            }
        );

        const selectedMeals = ref([]);
        const showSelected = ref(false);

        const addMeal = (meal) => {
            selectedMeals.value.push(meal);
        };

        const removeMeal = (meal) => {
            selectedMeals.value = selectedMeals.value.filter(
                (m) => m.id !== meal.id
            );
        };

        const [saving, save] = httpAction(
            () =>
                store.dispatch("menus/assignFreeRecipes", {
                    menu_id: menu.value.id,
                    meal_ids: selectedMeals.value.map((m) => m.id),
                }),
            () => {
                showSuccess("Free recipes saved");
            },
            () => showError("Failed to save free recipes")
        );

        onMounted(() => {
            store.dispatch("menus/fetchMenus");
        });

        return {
            menu,
            selectedMeals,
            showSelected,
            addMeal,
            removeMeal,
            save,
            saving,
        };
    },
};
</script>
