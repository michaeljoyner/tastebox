import { del, get, post } from "./http";

function fetchDiscountCodes() {
    return get("/admin/api/discount-codes");
}

function createDiscountCode(formData) {
    return post("/admin/api/discount-codes", formData);
}

function updateDiscountCode(code_id, formData) {
    return post(`/admin/api/discount-codes/${code_id}`, formData);
}

function deleteDiscountCode(code_id) {
    return del(`/admin/api/discount-codes/${code_id}`);
}

export {
    fetchDiscountCodes,
    createDiscountCode,
    updateDiscountCode,
    deleteDiscountCode,
};
