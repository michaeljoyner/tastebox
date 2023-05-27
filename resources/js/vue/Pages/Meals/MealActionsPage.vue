<template>
    <div>
        <sub-header title="Meal Actions"></sub-header>

        <meal-publish-toggle
            :is-public="meal.is_public"
            :meal-id="meal.id"
            @toggled="$store.dispatch('meals/refresh')"
            class="p-4 shadow my-12"
        ></meal-publish-toggle>

        <div class="my-12 shadow p-6">
            <form
                :action="`/admin/api/meals/${meal.id}/recipe-card`"
                method="POST"
                @submit="setRecipeTimer"
            >
                <p class="font-bold">Download Recipe Card</p>
                <p class="my-4 text-sm">
                    Download a PDF file of the recipe for this meal. Note it may
                    take a second or two, so be patient. Don't just click the
                    button like an angry monkey.
                </p>
                <input type="hidden" name="_token" :value="csrf_token" />
                <submit-button :waiting="waiting_on_recipe"
                    >Download PDF</submit-button
                >
            </form>
        </div>

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
import MealPublishToggle from "../../Components/Meals/MealPublishToggle.vue";
import SubHeader from "../../Components/UI/SubHeader.vue";
import CopyMeal from "../../Components/Meals/CopyMeal.vue";
import DeleteConfirmation from "../../Components/UI/DeleteConfirmation.vue";
import { showError, showSuccess } from "../../../libs/notifications.js";
import SubmitButton from "../../Components/UI/SubmitButton.vue";
export default {
    components: {
        SubmitButton,
        CopyMeal,
        SubHeader,
        MealPublishToggle,
        DeleteConfirmation,
    },

    props: ["meal"],

    data() {
        return {
            waiting_on_delete: false,
            waiting_on_recipe: false,
            csrf_token: document.getElementById("csrf-token-meta").content,
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

        setRecipeTimer() {
            this.waiting_on_recipe = true;
            window.setTimeout(() => (this.waiting_on_recipe = false), 5000);
        },
    },
};
</script>
