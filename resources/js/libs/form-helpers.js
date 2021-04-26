function createMessageBag(structure) {
    return Object.keys(structure).reduce((carry, key) => {
        carry[key] = "";
        return carry;
    }, {});
}

function createFieldsWithData(structure, data) {
    return Object.keys(structure).reduce((carry, key) => {
        carry[key] = data[key] || structure[key];
        return carry;
    }, {});
}

function fillErrorBag(errors, bag) {
    return Object.keys(bag).reduce((bag, key) => {
        bag[key] = errors.hasOwnProperty(key) ? errors[key][0] : "";
        return bag;
    }, {});
}

export { createMessageBag, createFieldsWithData, fillErrorBag };
