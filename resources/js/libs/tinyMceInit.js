function tinymceInitConfig(options) {
    return {
        selector: `#${options.id}`,
        plugins: "paste table link lists",
        toolbar: makeToolbar(options.allow_images),
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
        },
        images_upload_handler: options.handleUpload,
    };
}

function makeToolbar(hasImages) {
    if (hasImages) {
        return "undo redo | styleselect | link bold italic | bullist numlist table | imageBtn";
    }

    return "undo redo | styleselect | link bold italic | bullist numlist table";
}

export { tinymceInitConfig };
