function fileTooBig(file, size) {
    return file.size > size * 1000 * 1024;
}

function fileIsImage(file) {
    return file.type.indexOf("image") === 0;
}

export { fileTooBig, fileIsImage };
