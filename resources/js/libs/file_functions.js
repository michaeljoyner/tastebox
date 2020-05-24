function fileTooBig(file, mb) {
    return file.size > mb * 1024 * 1024;
}

function fileIsImage(file) {
    return file.type.indexOf("image") === 0;
}

export { fileTooBig, fileIsImage };
