function makeId() {
    return "id-" + window.crypto.randomUUID();
}

export { makeId };
