<template>
    <div class="">
        <div class="flex flex-1" style="height: calc(100vh - 7rem)">
            <div class="w-96 px-6 py-8 h-full overflow-auto">
                <div class="my-6">
                    <p class="form-label mb-2">Publish Status</p>
                    <div class="flex justify-between items-center">
                        <p>Make post public?</p>
                        <toggle-switch
                            :status="post.is_public"
                            :busy="publishing"
                            @toggled="togglePublishStatus"
                        ></toggle-switch>
                    </div>
                </div>
                <input-field
                    class="mb-6"
                    label="Post title"
                    :error-msg="form.errors.title"
                    v-model="form.data.title"
                ></input-field>
                <text-area-field
                    class="mb-6"
                    label="Description"
                    help-text="A brief description for Google"
                    v-model="form.data.description"
                ></text-area-field>
                <text-area-field
                    class="mb-6"
                    label="Introduction"
                    help-text="Introduction for the main blog page, etc"
                    v-model="form.data.intro"
                ></text-area-field>
                <div class="my-6">
                    <span class="form-label">Post Title Image</span>
                    <image-upload
                        :url="`/admin/api/blog/${post.id}/title-image`"
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
                    </image-upload>
                </div>
            </div>
            <div class="flex-1 px-6 flex flex-col">
                <h1 class="px-6 my-8 text-3xl font-semibold">
                    {{ form.data.title || `[Untitled]` }}
                </h1>
                <div class="flex-1" ref="editor_container">
                    <editor
                        v-if="editor_height"
                        :upload-to="`/admin/api/blog/${post.id}/images`"
                        v-model="form.data.body"
                        :height="editor_height"
                    ></editor>
                </div>
            </div>
        </div>
        <div
            class="h-12 flex justify-between items-center bg-white border-t border-gray-200 px-6"
        >
            <div>
                <delete-confirmation
                    :disabled="deleting"
                    @confirmed="deletePost"
                    :item="post.title"
                ></delete-confirmation>
            </div>
            <div class="">
                <a
                    :href="`/admin/blog/posts/${post.id}/preview`"
                    class="mx-4 text-gray-600 hover:text-green-600"
                    target="_blank"
                    >Preview</a
                >
                <button class="btn-main btn" type="button" @click="savePost">
                    Save
                </button>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import { reactive, ref, computed, onMounted } from "vue";
import TextAreaField from "../Forms/TextAreaField.vue";
import InputField from "../Forms/InputField.vue";
import Editor from "../Editor.vue";
import { useStore } from "vuex";
import { useForm } from "../../../libs/useForm.js";
import { showError, showSuccess } from "../../../libs/notifications.js";
import ImageUpload from "../ImageUpload.vue";
import { useImageUpload } from "../../../libs/useImageUpload.js";
import ToggleSwitch from "../Forms/ToggleSwitch.vue";
import { useRouter } from "vue-router";
import DeleteConfirmation from "../UI/DeleteConfirmation.vue";
export default {
    components: {
        DeleteConfirmation,
        ToggleSwitch,
        ImageUpload,
        Editor,
        InputField,
        TextAreaField,
    },

    props: ["post"],

    setup(props) {
        const { form, handleFormError, clearFormErrors } = useForm({
            title: props.post.title,
            intro: props.post.intro,
            description: props.post.description,
            body: props.post.body,
        });

        const store = useStore();
        const router = useRouter();

        const savePost = () => {
            clearFormErrors();
            store
                .dispatch("blog/update", {
                    post_id: props.post.id,
                    formData: form.data,
                })
                .then(() => showSuccess("Hey presto"))
                .catch((resp) => handleFormError(resp, "failed to save post"));
        };

        const { handleInvalidType, handleInvalidSize, handleUploadError } =
            useImageUpload();

        const showImage = ref(null);
        const imageSrc = computed(() => {
            return showImage.value
                ? showImage.value
                : props.post.title_image.sharing;
        });
        const setImage = (src) => {
            showImage.value = src;
            store.dispatch("blog/refresh");
        };

        const editor_container = ref(null);
        const editor_height = ref(null);
        onMounted(() => {
            editor_height.value = `${
                editor_container.value.getBoundingClientRect().height - 10
            }px`;
        });

        const publishing = ref(false);
        const togglePublishStatus = () => {
            publishing.value = true;
            const action = props.post.is_public
                ? "blog/retract"
                : "blog/publish";
            store
                .dispatch(action, props.post.id)
                .catch(() => showError("Failed to set publish status"))
                .then(() => (publishing.value = false));
        };

        const deleting = ref(false);
        const deletePost = () => {
            deleting.value = true;
            return store
                .dispatch("blog/delete", props.post.id)
                .then(() => {
                    showSuccess("Post deleted");
                    router.push("/blog");
                })
                .catch(() => showError("Failed to delete post"))
                .then(() => (deleting.value = false));
        };

        return {
            form,
            savePost,
            handleUploadError,
            handleInvalidType,
            handleInvalidSize,
            imageSrc,
            setImage,
            editor_container,
            editor_height,
            togglePublishStatus,
            publishing,
            deleting,
            deletePost,
        };
    },
};
</script>
