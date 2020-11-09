<template>
    <div>
        <input
            type="file"
            accept="image/*"
            class="hidden"
            ref="file_input"
            @change="handleFiles"
        />
        <div class="flex justify-between items-start">
            <p class="max-w-sm text-xs">
                Drag and drop images into the box below, or use the button to
                browse files. Note that only regular image files are accepted.
                You may drag the uploaded images into the order you desire.
            </p>
            <button class="btn btn-second" @click="$refs.file_input.click()">
                Browse files
            </button>
        </div>
        <div
            @drop.prevent="handleFiles"
            @dragenter.prevent="hover = true"
            @dragover.prevent="hover = true"
            @dragleave.prevent="hover = false"
            class="my-16 min-h-80 border-4 border-dotted rounded-lg"
            :class="{ 'border-blue-600': hover, 'border-gray-500': !hover }"
        >
            <div class="flex flex-wrap" ref="sortable">
                <sortable-gallery-image
                    v-for="image in storedImages"
                    :key="image.id"
                    :image="image"
                    :delete-url="imageDeleteUrl(image)"
                    @deleted="removeImage(image)"
                ></sortable-gallery-image>
            </div>
        </div>
        <div class="fixed bottom-0 right-0 p-6">
            <uploading-image
                v-for="upload in uploads"
                :key="upload.name"
                :file="upload"
                :upload-path="uploadPath"
                @uploaded="addImage"
            ></uploading-image>
        </div>
    </div>
</template>

<script type="text/babel">
import UploadingImage from "./UploadingImage";
import Sortable from "sortablejs";
import SortableGalleryImage from "./SortableGalleryImage";
import { showError } from "../../libs/notifications";
import { fileIsImage, fileTooBig } from "../../libs/file_functions";

export default {
    props: ["upload-path", "stored-images", "image-delete-url"],

    components: {
        UploadingImage,
        SortableGalleryImage,
    },

    data() {
        return {
            hover: false,
            uploads: [],
            sortable: null,
        };
    },

    mounted() {
        this.sortable = Sortable.create(this.$refs.sortable, {
            onUpdate: this.onOrderChange,
        });
    },

    methods: {
        onOrderChange() {
            this.$emit("reordered", this.sortable.toArray());
        },

        handleFiles(ev) {
            this.hover = false;
            const files = ev.target.files || ev.dataTransfer.files;

            [...files].forEach((file) => this.checkFile(file));
        },

        checkFile(file) {
            if (!fileTooBig(file, 20) && fileIsImage(file)) {
                return this.uploads.push(file);
            }

            showError(`${file.name} is not an acceptable file.`);
        },

        addImage({ data, file }) {
            this.uploads = this.uploads.filter((f) => f !== file);
            this.$emit("new-image", data);
        },

        removeImage(image) {
            this.$emit("image-removed", image);
        },
    },
};
</script>
