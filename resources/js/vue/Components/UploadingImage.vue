<template>
    <div class="w-48 h-32 relative">
        <img
            :src="src"
            class="w-full h-full object-cover"
            :class="upload_opacity"
        />
        <div
            class="absolute h-2 left-0 bottom-0 right-0 bg-pink-500"
            style="transform-origin: left;"
            :style="`transform: scaleX(${progress})`"
        ></div>
    </div>
</template>

<script type="text/babel">
import { generatePreview } from "../../libs/generate_preview";
import { showError } from "../../libs/notifications";

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
