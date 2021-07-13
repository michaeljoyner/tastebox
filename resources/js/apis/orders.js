import { get } from "./http";

function fetchRecentOrders() {
    return get("/admin/api/recent-orders");
}

function fetchById(id) {
    return get(`/admin/api/recent-orders/${id}`);
}

function fetchOrderedKits() {
    return get("/admin/api/ordered-kits");
}

export { fetchRecentOrders, fetchById, fetchOrderedKits };
