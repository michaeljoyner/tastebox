import { get, post } from "./http";

const fetchOrderedKits = (page) => {
    return get(`/admin/api/ordered-kits?page=${page}`);
};

const fetchKitById = (kit_id) => {
    return get(`/admin/api/ordered-kits/${kit_id}`);
};

const updateKitMeals = (kit_id, formData) => {
    return post(`/admin/api/ordered-kits/${kit_id}`, formData);
};

const cancelKit = (kit_id, reason) =>
    post("/admin/api/cancelled-kits", { kit_id, reason });

export { fetchOrderedKits, fetchKitById, updateKitMeals, cancelKit };
