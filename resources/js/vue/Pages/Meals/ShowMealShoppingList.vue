<template>
    <Page v-if="list">
        <PageHeader :title="`Shopping List #${list.id}`">
            <a
                :href="`/admin/api/meal-shopping-lists/${list.uuid}/pdf`"
                class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 text-sm rounded-md"
                >Download PDF</a
            >
        </PageHeader>

        <div class="my-12 bg-slate-50 p-4 rounded-lg">
            <p class="text-sm font-semibold">List for:</p>
            <div v-for="entry in list.meal_entries" :key="entry.id">
                <p>{{ entry.meal.name }} x {{ entry.servings }}</p>
            </div>
        </div>

        <ShoppingListItems :items="list.shopping_list" />
    </Page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import { fetchMealShoppingList } from "../../../apis/meals";
import { showError } from "../../../libs/notifications";
import ShoppingListItems from "../../Components/Meals/ShoppingListItems.vue";

const route = useRoute();

const list = ref(null);

const fetchList = () =>
    fetchMealShoppingList(route.params.list)
        .then(({ data }) => (list.value = data))
        .catch(() => showError("Failed to fetch list"));

onMounted(() => {
    fetchList();
});
</script>
