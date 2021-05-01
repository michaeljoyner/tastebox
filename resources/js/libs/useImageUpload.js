import { showError, showSuccess } from "./notifications";

function handleInvalidType() {
    showError("That image is not a valid file type");
}

function handleInvalidSize() {
    showError("That image is too big");
}

function handleUploadError() {
    showError("Failed to upload image");
}

function handleUploadSuccess() {
    showSuccess("Image uploaded successfully");
}

function useImageUpload() {
    return {
        handleInvalidType,
        handleInvalidSize,
        handleUploadError,
        handleUploadSuccess,
    };
}

export { useImageUpload };
