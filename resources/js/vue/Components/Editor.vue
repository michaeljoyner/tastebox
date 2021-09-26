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
        <modal :show="showVideoEmbedModal" @close="showVideoEmbedModal = false">
            <div class="max-w-md w-full mx-auto bg-white rounded-lg p-6">
                <p class="font-lg font-semibold">Embed a Youtube Video</p>
                <p class="text-sm text-gray-500 my-3">
                    Copy in the Youtube embed code below to add a video to your
                    post.
                </p>
                <text-area-field v-model="videoEmbedCode"></text-area-field>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="showVideoEmbedModal = false">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="embedVideo"
                        class="btn btn-main ml-4"
                    >
                        Add Video
                    </button>
                </div>
            </div>
        </modal>

        <modal :show="showCodeEmbedModal" @close="showCodeEmbedModal = false">
            <div class="max-w-md w-full mx-auto bg-white rounded-lg p-6">
                <p class="font-lg font-semibold">Embed Code</p>
                <p class="text-sm text-gray-500 my-3">
                    Paste in the code you need to embed into the box below.
                </p>
                <text-area-field v-model="embedCode"></text-area-field>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="showCodeEmbedModal = false">
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="insertEmbed"
                        class="btn btn-main ml-4"
                    >
                        Insert
                    </button>
                </div>
            </div>
        </modal>
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
import Modal from "./Modal";
import TextAreaField from "./Forms/TextAreaField";
export default {
    components: { TextAreaField, Modal },
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
        compact: {
            type: Boolean,
            default: false,
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
            allow_youtube: true,
            allow_embeds: true,
            handleImageBtnClick: () => image_file_input.value.click(),
            handleVideoBtnClick: () => (showVideoEmbedModal.value = true),
            handleEmbedBtnClick: () => (showCodeEmbedModal.value = true),
            handleUpload,
            emit,
            is_compact: props.compact,
        };

        onMounted(async () => {
            const editors = await tinymce.init(tinymceInitConfig(options));
            theEditor = editors[0];
        });

        const showVideoEmbedModal = ref(false);
        const videoEmbedCode = ref("");

        const embedVideo = () => {
            const markup = `<div class="video-embed">
${videoEmbedCode.value}
</div><p></p>`;
            theEditor.insertContent(markup);
            videoEmbedCode.value = "";
            showVideoEmbedModal.value = false;
        };

        const showCodeEmbedModal = ref(false);
        const embedCode = ref("");

        const insertEmbed = () => {
            const markup = `<div class="embeded-code">
${embedCode.value}
</div><p></p>`;

            theEditor.insertContent(markup);

            const exists = theEditor
                .getWin()
                .document.getElementById("ig-embed-code-script");

            if (exists) {
                exists.parentNode.removeChild(exists);
            }

            const script = embedCode.value.match(/<script.*<\/script>/)[0];
            const scriptSrc = script.match(/".*\.js/)[0].split('"')[1];

            const scr = document.createElement("script");
            scr.setAttribute("id", "ig-embed-code-script");
            scr.setAttribute("src", scriptSrc);
            scr.setAttribute("type", "text/javascript");
            const frameHead = theEditor.getWin().document.head;
            frameHead.appendChild(scr);
            embedCode.value = "";
            showCodeEmbedModal.value = false;
        };

        return {
            unique_id,
            image_file_input,
            insertImage,
            theEditor,
            showVideoEmbedModal,
            videoEmbedCode,
            embedVideo,
            showCodeEmbedModal,
            insertEmbed,
            embedCode,
        };
    },
};
</script>
