import { get } from "./http";

function fetchOrders(page) {
    return get(`/admin/api/orders?page=${page}`);
}

function fetchById(id) {
    return get(`/admin/api/recent-orders/${id}`);
}

function fetchUpcomingKits() {
    return get("/admin/api/upcoming-ordered-kits");
}

export { fetchOrders, fetchById, fetchUpcomingKits };
