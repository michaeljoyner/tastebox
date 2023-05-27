<template>
    <div class="my-12 pb-6 border-b border-gray-400">
        <h1 class="text-3xl font-semibold">{{ meal.name }}</h1>
        <div class="flex justify-end items-center mt-6">
            <copy-meal :meal="meal"></copy-meal>
            <router-link :to="`/meals/${meal.id}/gallery`" class="btn mx-4"
                >Pics</router-link
            >
            <router-link :to="`/meals/${meal.id}/edit`" class="btn btn-main"
                >Edit</router-link
            >
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
import CopyMeal from "./CopyMeal.vue";
import DeleteConfirmation from "../UI/DeleteConfirmation.vue";
import { showError } from "../../../libs/notifications.js";
export default {
    components: {
        CopyMeal,
        DeleteConfirmation,
    },

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
                .then(() => this.$router.push("/meals"))
                .catch(() => showError("Unable to delete meal."))
                .then(() => (this.waiting_on_delete = false));
        },
    },
};
</script>
