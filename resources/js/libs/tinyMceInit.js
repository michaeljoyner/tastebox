function tinymceInitConfig(options) {
    return {
        selector: `#${options.id}`,
        plugins: "paste table link lists",
        toolbar: makeToolbar(options.allow_images, options.allow_youtube),
        menubar: "",
        content_style: "img {max-width: 100%;}",
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
        },
        images_upload_handler: options.handleUpload,
    };
}

function makeToolbar(hasImages, hasVideo) {
    if (hasImages || hasVideo) {
        return `undo redo | styleselect | link bold italic | bullist numlist table | ${
            hasImages ? "imageBtn " : ""
        }${hasVideo ? "videoBtn" : ""}`;
    }

    return `undo redo | styleselect | link bold italic | bullist numlist table`;
}

export { tinymceInitConfig };
