import { get, post } from "./http";

const fetchAdjustments = (page) => {
    return get(`/admin/api/adjustments?page=${page}`);
};

const fetchUnresolvedAdjustments = () => {
    return get("/admin/api/unresolved-adjustments");
};

const fetchAdjustmentById = (id) => {
    return get(`/admin/api/adjustments/${id}`);
};

const resolveAdjustment = (adjustment_id, note) => {
    return post("/admin/api/resolved-adjustments", { adjustment_id, note });
};

export {
    fetchAdjustments,
    fetchUnresolvedAdjustments,
    fetchAdjustmentById,
    resolveAdjustment,
};
