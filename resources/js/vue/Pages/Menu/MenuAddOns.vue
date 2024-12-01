<template>
    <Page v-if="menu">
        <PageHeader :title="`Add-Ons for #${menu.week_number}`">
            <RouterLink :to="`/menus/${menu.id}`" class="muted-text-btn"
                >&larr; Back to Menu</RouterLink
            >
        </PageHeader>

        <div class="my-12">
            <MenuAddOnsForm :menu="menu" />
        </div>
    </Page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";
import MenuAddOnsForm from "../../Components/Menu/MenuAddOnsForm.vue";

const store = useStore();
const route = useRoute();
const router = useRouter();

const menu = computed(() => store.getters["menus/byId"](route.params.menu));
const categories = computed(() => store.state.addons.categories);

onMounted(() => {
    store.dispatch("menus/fetchMenus");
    store.dispatch("addons/fetchCategories");
});
</script>
