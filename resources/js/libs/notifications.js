import { store } from "../stores";

function showSuccess(message) {
    store.commit("notifications/addSuccess", message);
}

function showError(message) {
    store.commit("notifications/addError", message);
}

function showWarning(message) {
    store.commit("notifications/addWarning", message);
}

export { showSuccess, showError, showWarning };
