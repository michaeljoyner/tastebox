<template>
    <Page v-if="addon">
        <PageHeader :title="`${addon.name}`">
            <button
                type="button"
                class="muted-text-btn"
                @click="showEditModal = true"
            >
                Edit
            </button>

            <button class="mx-4 muted-text-btn" @click="showDeleteModal = true">
                Delete
            </button>

            <RouterLink
                :to="`/addon-categories/${addon.category.uuid}`"
                class="muted-text-btn"
                >&larr; Back</RouterLink
            >
        </PageHeader>

        <div class="my-12 flex gap-12">
            <div class="w-1/2">
                <p
                    class="text-xl font-black inline-block px-3 py-2 rounded-md bg-gray-200"
                >
                    {{ addon.price_formatted }}
                </p>
                <p class="text-lg text-gray-500 mt-4 p-2">
                    {{ addon.description }}
                </p>
            </div>
            <div>
                <ImageUpload
                    :url="`/admin/api/add-ons/${addon.uuid}/image`"
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

        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="w-full mx-auto max-w-2xl p-6 rounded-lg bg-white">
                <AddOnForm
                    :addon="addon"
                    :category-uuid="addon.category.uuid"
                    @cancel="showEditModal = false"
                    @done="showEditModal = false"
                />
            </div>
        </Modal>

        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="w-full mx-auto max-w-md p-6 rounded-lg bg-white">
                <p class="text-lg font-semibold">Delete this item?</p>
                <p class="my-4 text-gray-500">
                    This will permanently delete this item. It will not affect
                    any existing orders.
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
import { useRoute, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";
import Modal from "../../Components/Modal.vue";
import AddOnForm from "../../Components/AddOns/AddOnForm.vue";
import ImageUpload from "../../Components/ImageUpload.vue";
import { useImageUpload } from "../../../libs/useImageUpload";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import SubmitButton from "../../Components/UI/SubmitButton.vue";

const store = useStore();
const route = useRoute();
const router = useRouter();

const addon = computed(() => store.state.addons.active);

const showEditModal = ref(false);

const { handleInvalidType, handleInvalidSize, handleUploadError } =
    useImageUpload();

const showImage = ref(null);
const imageSrc = computed(() => {
    return showImage.value
        ? showImage.value
        : addon.value.image.web || "/images/default-placeholder.svg";
});
const setImage = (src) => {
    showImage.value = src;
    store.dispatch("addons/refreshActive", addon.value.uuid);
};

const showDeleteModal = ref(false);
const [deleting, deleteAddOn] = httpAction(
    () => store.dispatch("addons/deleteAddOn", addon.value.uuid),
    () => {
        const category_uuid = addon.value.category.uuid;
        showSuccess("Add on deleted");
        router.push(`/addon-categories/${category_uuid}`);
    },
    () => showError("Failed to delete add on")
);

onMounted(() => {
    store.dispatch("addons/fetchActive", route.params.addon);
});
</script>
