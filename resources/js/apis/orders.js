import { get } from "./http";

function fetchRecentOrders() {
    return get("/admin/api/recent-orders");
}

function fetchById(id) {
    return get(`/admin/api/recent-orders/${id}`);
}

export { fetchRecentOrders, fetchById };
