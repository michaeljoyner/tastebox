import { del, get, post } from "./http";

function fetchAddOnCategories() {
    return get("/admin/api/add-on-categories");
}

function fetchAddOnCategory(uuid) {
    return get(`/admin/api/add-on-categories/${uuid}`);
}

function createAddOnCategory(formData) {
    return post(`/admin/api/add-on-categories`, formData);
}

function updateAddOnCategory(uuid, formData) {
    return post(`/admin/api/add-on-categories/${uuid}`, formData);
}

function deleteAddOnCategory(uuid) {
    return del(`/admin/api/add-on-categories/${uuid}`);
}

function fetchAddOns() {
    return get("/admin/api/add-ons");
}

function fetchAddOn(uuid) {
    return get(`/admin/api/add-ons/${uuid}`);
}

function createAddOn(category_uuid, formData) {
    return post(
        `/admin/api/add-on-categories/${category_uuid}/add-ons`,
        formData
    );
}

function updateAddOn(uuid, formData) {
    return post(`/admin/api/add-ons/${uuid}`, formData);
}

function deleteAddOn(uuid) {
    return del(`/admin/api/add-ons/${uuid}`);
}

export {
    fetchAddOnCategories,
    fetchAddOnCategory,
    createAddOnCategory,
    updateAddOnCategory,
    deleteAddOnCategory,
    fetchAddOns,
    fetchAddOn,
    createAddOn,
    updateAddOn,
    deleteAddOn,
};
