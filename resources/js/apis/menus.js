import { del, post } from "./http";

function setMenuMeals(menu_id, meal_ids) {
    return post(`/admin/api/menus/${menu_id}/meals`, { meal_ids });
}

function openMenuForOrders(menu_id) {
    return post(`/admin/api/orderable-menus`, { menu_id });
}

function closeMenuForOrders(menu_id) {
    return del(`/admin/api/orderable-menus/${menu_id}`);
}

export { setMenuMeals, openMenuForOrders, closeMenuForOrders };
