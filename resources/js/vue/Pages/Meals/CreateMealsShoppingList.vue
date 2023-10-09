<template>
    <Page>
        <PageHeader title="Create a Shopping List"></PageHeader>

        <div v-show="!creating">
            <MealServingsList
                @selectionComplete="createList"
            ></MealServingsList>
        </div>

        <div v-show="creating" class="flex items-center space-x-2">
            <SpinningIcon class="text-pink-500 w-4 h-4" />
            <p class="text-gray-500">Stand by, generating list</p>
        </div>
    </Page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import MealServingsList from "../../Components/Meals/MealServingsList.vue";
import { httpAction } from "../../../libs/httpAction";
import { createMealShoppingList } from "../../../apis/meals";
import { showError, showSuccess } from "../../../libs/notifications";
import SpinningIcon from "../../Components/Icons/SpinningIcon.vue";
import { useRouter } from "vue-router";

const router = useRouter();

const [creating, createList] = httpAction(
    (meals) => createMealShoppingList(meals),
    ({ data }) => {
        showSuccess("Time to go shopping!!!!");
        router.push(`/shopping-list/${data.uuid}`);
    },
    () => showError("Failed to create shopping list")
);
</script>
