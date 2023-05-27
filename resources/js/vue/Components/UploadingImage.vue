<template>
    <div class="w-64 flex items-center">
        <div class="h-8 w-8 rounded-full overflow-hidden">
            <img
                :src="src"
                class="w-full h-full object-cover"
                :class="upload_opacity"
            />
        </div>
        <div class="flex-1 h-2 px-3">
            <div
                class="h-full w-48 bg-blue-600 rounded-lg relative"
                style="transform-origin: left"
                :style="`transform: scaleX(${progress})`"
            ></div>
        </div>
    </div>
</template>

<script type="text/babel">
import { generatePreview } from "../../libs/generate_preview.js";
import { showError } from "../../libs/notifications.js";

export default {
    props: ["file", "upload-path"],

    data() {
        return {
            src: null,
            progress: 0,
        };
    },

    computed: {
        upload_opacity() {
            if (this.progress > 0.75) {
                return "opacity-75";
            }

            return "opacity-50";
        },
    },

    mounted() {
        generatePreview(this.file)
            .then((src) => (this.src = src))
            .catch(showError);

        this.upload();
    },

    methods: {
        upload() {
            const fd = new FormData();
            fd.append("image", this.file);

            axios
                .post(this.uploadPath, fd, {
                    onUploadProgress: (ev) =>
                        (this.progress = ev.loaded / ev.total),
                })
                .then(({ data }) =>
                    this.$emit("uploaded", { data, file: this.file })
                )
                .catch(() => showError(`Failed to upload ${this.file.name}`));
        },
    },
};
</script>
