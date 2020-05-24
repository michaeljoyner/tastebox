function generatePreview(file, width, height) {
    return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        const preview = new Image();

        fileReader.onload = (ev) => resolve(ev.target.result);
        fileReader.onerror = reject;

        fileReader.readAsDataURL(file);
    });
}

export { generatePreview };
