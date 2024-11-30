<template>
    <Page v-if="category">
        <PageHeader :title="category.name">
            <button
                class="muted-text-btn"
                type="button"
                @click="showEditModal = true"
            >
                Edit
            </button>

            <button class="mx-4 muted-text-btn" @click="showDeleteModal = true">
                Delete
            </button>

            <RouterLink to="/addon-categories" class="muted-text-btn text-sm"
                >&larr; Back</RouterLink
            >
        </PageHeader>

        <div class="my-12 flex gap-12">
            <p class="text-lg text-gray-500 w-1/2">
                {{ category.description }}
            </p>

            <div>
                <ImageUpload
                    :url="`/admin/api/add-on-categories/${category.uuid}/image`"
                    v-slot="{ progress, uploading }"
                    @uploaded="setImage"
                    @preview="setImage"
                    @error="handleUploadError"
                    @invalid:type="handleInvalidType"
                    @invalid:size="handleInvalidSize"
                >
                    <div class="w-full h-52 relative">
                        <img
                            :class="{ 'opacity-50': uploading }"
                            class="w-full h-full object-cover"
                            :src="imageSrc"
                            alt=""
                        />
                        <div
                            class="absolute inset-0 flex justify-center items-center text-3xl text-white"
                            v-show="uploading"
                        >
                            {{ progress }} %
                        </div>
                    </div>
                </ImageUpload>
            </div>
        </div>

        <div class="my-12">
            <div
                class="flex justify-between items-center border-b border-gray-200 pb-2"
            >
                <p class="text-lg font-bold">Add On Items</p>

                <button class="muted-text-btn" @click="showCreateModal = true">
                    + New
                </button>
            </div>

            <div>
                <p class="my-4 text-gray-500" v-show="!category.add_ons.length">
                    There are no add-on items in this category yet.
                </p>
                <div
                    v-for="addon in category.add_ons"
                    :key="addon.uuid"
                    class="p-4 rounded-lg shadow my-4 flex items-center justify-between"
                >
                    <div>
                        <p>
                            <RouterLink
                                :to="`/add-ons/${addon.uuid}`"
                                class="hover:text-pink-500 font-semibold"
                            >
                                {{ addon.name }}
                            </RouterLink>
                        </p>
                        <p class="text-sm text-gray-500 max-w-sm truncate">
                            {{ addon.description }}
                        </p>
                    </div>
                    <p class="font-black text-xl text-slate-700">
                        {{ addon.price_formatted }}
                    </p>
                </div>
            </div>
        </div>

        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="w-full mx-auto max-w-2xl p-6 rounded-lg bg-white">
                <AddOnCategoryForm
                    :category="category"
                    @done="showEditModal = false"
                    @cancel="showEditModal = false"
                />
            </div>
        </Modal>

        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="w-full mx-auto max-w-2xl p-6 rounded-lg bg-white">
                <AddOnForm
                    :category-uuid="category.uuid"
                    @cancel="showCreateModal = false"
                    @done="showCreateModal = false"
                />
            </div>
        </Modal>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="w-full mx-auto max-w-md p-6 rounded-lg bg-white">
                <p class="text-lg font-semibold">Delete this category?</p>
                <p class="my-4 text-gray-500">
                    This will permanently delete this category and all its
                    associated add-ons. It will not affect any existing orders.
                </p>
                <div class="flex justify-end mt-6 gap-4">
                    <button
                        type="button"
                        class="muted-text-btn"
                        @click="showDeleteModal = false"
                    >
                        Cancel
                    </button>
                    <SubmitButton :waiting="deleting" @click="deleteAddOn"
                        >Yes, delete it!</SubmitButton
                    >
                </div>
            </div>
        </Modal>
    </Page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import Modal from "../../Components/Modal.vue";
import AddOnCategoryForm from "../../Components/AddOns/AddOnCategoryForm.vue";
import ImageUpload from "../../Components/ImageUpload.vue";
import { useImageUpload } from "../../../libs/useImageUpload";
import AddOnForm from "../../Components/AddOns/AddOnForm.vue";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import SubmitButton from "../../Components/UI/SubmitButton.vue";

const store = useStore();
const route = useRoute();
const router = useRouter();

const category = computed(() => store.state.addons.active_category);

const showEditModal = ref(false);

const { handleInvalidType, handleInvalidSize, handleUploadError } =
    useImageUpload();

const showImage = ref(null);
const imageSrc = computed(() => {
    return showImage.value
        ? showImage.value
        : category.value.image.web || "/images/default-placeholder.svg";
});
const setImage = (src) => {
    showImage.value = src;
    store.dispatch("addons/refreshActiveCategory", category.value.uuid);
};

const showCreateModal = ref(false);

const showDeleteModal = ref(false);
const [deleting, deleteAddOn] = httpAction(
    () => store.dispatch("addons/deleteCategory", category.value.uuid),
    () => {
        showSuccess("Category deleted");
        router.push(`/addon-categories`);
    },
    () => showError("Failed to delete category")
);

onMounted(() => {
    store.dispatch("addons/fetchActiveCategory", route.params.category);
});
</script>
