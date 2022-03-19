import { del, post, get } from "./http";

function setMenuMeals(menu_id, meal_ids) {
    return post(`/admin/api/menus/${menu_id}/meals`, { meal_ids });
}

function openMenuForOrders(menu_id) {
    return post(`/admin/api/orderable-menus`, { menu_id });
}

function closeMenuForOrders(menu_id) {
    return del(`/admin/api/orderable-menus/${menu_id}`);
}

function getCurrentBatch() {
    return get("/admin/api/current-batch");
}

function placeManualOrder(formData) {
    return post("/admin/api/current-batch/manual-orders", formData);
}

function assignMenuFreeRecipes(menu_id, meal_ids) {
    return post(`/admin/api/menus/${menu_id}/free-recipes`, { meal_ids });
}

export {
    setMenuMeals,
    openMenuForOrders,
    closeMenuForOrders,
    getCurrentBatch,
    placeManualOrder,
    assignMenuFreeRecipes,
};
