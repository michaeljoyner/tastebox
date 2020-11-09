<template>
    <div>
        <sub-header title="Meal Actions"></sub-header>

        <meal-publish-toggle
            :is-public="meal.is_public"
            :meal-id="meal.id"
            @toggled="$store.dispatch('meals/refresh')"
            class="p-4 shadow my-12"
        ></meal-publish-toggle>

        <div class="my-12">
            <copy-meal :meal="meal"></copy-meal>
        </div>

        <div class="my-12 shadow p-6">
            <p class="font-bold">Delete Meal</p>
            <p class="my-4 text-sm">
                You can delete this meal, but remember, with great power comes
                great responsibility. Once the meal is deleted it will be
                forever gone, barely even a memory.
            </p>
            <delete-confirmation
                :disabled="waiting_on_delete"
                :item="meal.name"
                class="ml-4"
                @confirmed="deleteMeal"
            ></delete-confirmation>
        </div>
    </div>
</template>

<script type="text/babel">
import MealPublishToggle from "../../Components/Meals/MealPublishToggle";
import SubHeader from "../../Components/UI/SubHeader";
import CopyMeal from "../../Components/Meals/CopyMeal";
import DeleteConfirmation from "../../Components/UI/DeleteConfirmation";
import { showError, showSuccess } from "../../../libs/notifications";
export default {
    components: { CopyMeal, SubHeader, MealPublishToggle, DeleteConfirmation },

    props: ["meal"],

    data() {
        return {
            waiting_on_delete: false,
        };
    },

    methods: {
        deleteMeal() {
            this.waiting_on_delete = true;
            this.$store
                .dispatch("meals/deleteMealById", this.meal.id)
                .then(() => {
                    showSuccess("Meal deleted.");
                    this.$router.push("/meals");
                })
                .catch(() => showError("Unable to delete meal."))
                .then(() => (this.waiting_on_delete = false));
        },
    },
};
</script>
