<template>
    <div>
        <div :id="unique_id"></div>
        <div>
            <input
                type="file"
                class="hidden"
                ref="image_file_input"
                @input="insertImage"
            />
        </div>
    </div>
</template>

<script type="text/babel">
import { ref } from "vue";
import tinymce from "tinymce";
import "tinymce/themes/silver";
import "tinymce/plugins/table";
import "tinymce/plugins/paste";
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/icons/default";
import { onMounted } from "vue";
import { fileIsImage, fileTooBig } from "../../libs/files";
import { imageFromFile } from "../../libs/images";
import { upload } from "../../apis/http";
import { makeId } from "../../libs/helpers";
import { tinymceInitConfig } from "../../libs/tinyMceInit";
export default {
    props: {
        modelValue: {
            type: String,
            default: "",
        },
        height: {
            type: String,
            default: "500px",
        },
        uploadTo: {
            type: String,
            default: "",
        },
    },
    emits: [
        "update:modelValue",
        "error:imageTooLarge",
        "error:imageWrongType",
        "error:cannotLoadImage",
    ],
    setup(props, { emit }) {
        const image_file_input = ref(null);
        const unique_id = ref(makeId());
        const insertImage = ({ target }) => {
            const file = target.files[0];
            if (fileTooBig(file, 10)) {
                return emit("error:imageTooLarge");
            }

            if (!fileIsImage(file)) {
                return emit("error:imageWrongType");
            }

            imageFromFile(file)
                .then((src) => {
                    theEditor.insertContent(`<img src="${src}" >`);
                })
                .catch(() => emit("error:cannotLoadImage"));

            image_file_input.value.value = null;
        };

        const handleUpload = (blob, success, failure, progress) => {
            upload(props.uploadTo, blob.blob(), progress)
                .then(({ data }) => success(data.src))
                .catch(() => failure("Sorry, failed to upload"));
        };

        let theEditor = ref(null);

        const options = {
            id: unique_id.value,
            height: props.height,
            content: props.modelValue,
            allow_images: props.uploadTo !== "",
            handleImageBtnClick: () => image_file_input.value.click(),
            handleUpload,
            emit,
        };

        onMounted(async () => {
            const editors = await tinymce.init(tinymceInitConfig(options));
            theEditor = editors[0];
        });

        return {
            unique_id,
            image_file_input,
            insertImage,
            theEditor,
        };
    },
};
</script>
