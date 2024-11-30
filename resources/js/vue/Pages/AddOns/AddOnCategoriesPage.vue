<template>
    <Page>
        <PageHeader title="Add On Categories">
            <button
                class="muted-text-btn"
                type="button"
                @click="showCreateModal = true"
            >
                + New
            </button>
        </PageHeader>

        <div class="my-12">
            <div
                v-for="category in categories"
                :key="category.uuid"
                class="p-4 rounded-lg shadow mb-4"
            >
                <p class="mb-2">
                    <RouterLink
                        :to="`/addon-categories/${category.uuid}`"
                        class="text-lg font-bold hover:text-pink-500"
                        >{{ category.name }}</RouterLink
                    >
                </p>
                <p class="text-sm text-gray-500 max-w-sm truncate">
                    {{ category.description }}
                </p>
            </div>
        </div>

        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="w-full mx-auto max-w-2xl p-6 rounded-lg bg-white">
                <AddOnCategoryForm
                    @cancel="showCreateModal = false"
                    @done="showCreateModal = false"
                />
            </div>
        </Modal>
    </Page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { computed, onMounted, ref } from "vue";
import Modal from "../../Components/Modal.vue";
import AddOnCategoryForm from "../../Components/AddOns/AddOnCategoryForm.vue";
import { useStore } from "vuex";

const store = useStore();

const categories = computed(() => store.state.addons.categories);

const showCreateModal = ref(false);

onMounted(() => {
    store.dispatch("addons/fetchCategories");
});
</script>
