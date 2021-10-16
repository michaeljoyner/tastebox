import { get } from "./http";

function fetchOrders(page) {
    return get(`/admin/api/orders?page=${page}`);
}

function fetchById(id) {
    return get(`/admin/api/recent-orders/${id}`);
}

function fetchOrderedKits() {
    return get("/admin/api/ordered-kits");
}

export { fetchOrders, fetchById, fetchOrderedKits };
