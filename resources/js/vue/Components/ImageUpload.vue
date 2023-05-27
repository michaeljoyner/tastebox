<template>
    <label>
        <input
            class="hidden"
            type="file"
            accept="image/*"
            @input="handleFile"
            ref="file_input"
        />
        <slot :progress="progress" :uploading="uploading"></slot>
    </label>
</template>

<script type="text/babel">
import { computed, ref } from "vue";
import { fileIsImage, fileTooBig } from "../../libs/files.js";
import { upload } from "../../apis/http.js";
import { imageFromFile } from "../../libs/images.js";

export default {
    props: {
        url: {
            type: String,
        },
        initial: {
            type: String,
            default: "",
        },
        maxSize: {
            type: Number,
            default: 15,
        },
    },

    emits: ["preview", "uploaded", "error", "invalid:size", "invalid:type"],

    setup(props, { emit }) {
        const file_input = ref(null);
        const handleFile = ({ target }) => {
            const file = target.files[0];
            if (fileTooBig(file, props.maxSize)) {
                return emit("invalid:size");
            }

            if (!fileIsImage(file)) {
                return emit("invalid:type");
            }

            imageFromFile(file)
                .then((src) => emit("preview", src))
                .catch(() => {});

            uploading.value = true;
            upload(props.url, file, updateProgress)
                .then(({ data }) => {
                    emit("uploaded", data.src);
                })
                .catch(({ status }) => emit("error", status))
                .then(() => {
                    uploading.value = false;
                    file_input.value.value = null;
                });
        };

        const progress = ref(0);
        const updateProgress = (p) => (progress.value = p);

        const uploading = ref(false);

        return { handleFile, progress, uploading, file_input };
    },
};
</script>
