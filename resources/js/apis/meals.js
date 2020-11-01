import { del, get, post } from "./http";

function createNewMeal() {
    return post("/admin/api/meals");
}

function deleteMeal(meal_id) {
    return del(`/admin/api/meals/${meal_id}`);
}

function fetchAllMeals() {
    return get("/admin/api/meals");
}

function findMealById(meal_id) {
    return get(`/admin/api/meals/${meal_id}`);
}

function saveMeal(meal_id, formData) {
    return post(`/admin/api/meals/${meal_id}`, formData);
}

function copyMeal(meal_id, name) {
    return post(`/admin/api/meals/${meal_id}/copies`, { name });
}

export {
    deleteMeal,
    copyMeal,
    fetchAllMeals,
    findMealById,
    createNewMeal,
    saveMeal,
};
