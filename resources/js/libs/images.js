function imageFromFile(file) {
    return new Promise((resolve, reject) => {
        const fr = new FileReader();
        fr.onload = ({ target }) => {
            resolve(target.result);
        };
        fr.onerror = reject;

        fr.readAsDataURL(file);
    });
}

export { imageFromFile };
