const toStandardDateFormat = (date) => {
    if (!date) {
        return new Date().toLocaleDateString("en-CA");
    }

    return date.toLocaleDateString("en-CA");
};

export { toStandardDateFormat };
