import { get } from "./http";

const fetchOrderedKits = (page) => {
    return get(`/admin/api/ordered-kits?page=${page}`);
};

const fetchKitById = (kit_id) => {
    return get(`/admin/api/ordered-kits/${kit_id}`);
};

export { fetchOrderedKits, fetchKitById };
