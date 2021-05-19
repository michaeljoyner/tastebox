function tinymceInitConfig(options) {
    return {
        convert_urls: false,
        selector: `#${options.id}`,
        plugins: "paste table link lists",
        toolbar: makeToolbar(
            options.allow_images,
            options.allow_youtube,
            options.allow_embeds
        ),
        menubar: "",
        content_css: "document",
        content_style:
            "img {max-width: 100%;} body {max-width: 50rem; margin: 0 auto;}",
        height: options.height,
        setup(editor) {
            editor.on("init", () => {
                editor.setContent(options.content);
            });

            editor.on("change input undo redo", (ev) =>
                options.emit("update:modelValue", editor.getContent())
            );

            if (options.allow_images) {
                editor.ui.registry.addButton("imageBtn", {
                    icon: "image",
                    onAction() {
                        options.handleImageBtnClick();
                    },
                });
            }

            if (options.allow_youtube) {
                editor.ui.registry.addButton("videoBtn", {
                    icon: "embed",
                    onAction() {
                        options.handleVideoBtnClick();
                    },
                });
            }

            if (options.allow_embeds) {
                editor.ui.registry.addButton("embedBtn", {
                    icon: "sourcecode",
                    onAction() {
                        options.handleEmbedBtnClick();
                    },
                });
            }
        },
        images_upload_handler: options.handleUpload,
    };
}

function makeToolbar(hasImages, hasVideo, hasEmbed) {
    if (hasImages || hasVideo || hasEmbed) {
        return `undo redo | styleselect | link bold italic | bullist numlist table | ${
            hasImages ? "imageBtn " : ""
        }${hasVideo ? " videoBtn" : ""}${hasEmbed ? " embedBtn" : ""}`;
    }

    return `undo redo | styleselect | link bold italic | bullist numlist table`;
}

export { tinymceInitConfig };
