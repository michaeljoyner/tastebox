import { del, get, post } from "./http";

function createNewMeal(formData) {
    return post("/admin/api/meals", formData);
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

function updateMealInfo(meal_id, formData) {
    return post(`/admin/api/meals/${meal_id}`, formData);
}

function updateMealInstructions(meal_id, instructions) {
    return post(`/admin/api/meals/${meal_id}/instructions`, { instructions });
}

function updateMealIngredients(meal_id, ingredients) {
    return post(`/admin/api/meals/${meal_id}/ingredients`, { ingredients });
}

function updateMealNutritionalInfo(meal_id, formData) {
    return post(`/admin/api/meals/${meal_id}/nutritional-info`, formData);
}

function updateMealPublicRecipeNotes(meal_id, public_recipe_notes) {
    return post(`/admin/api/meals/${meal_id}/public-recipe-notes`, {
        public_recipe_notes,
    });
}

function copyMeal(meal_id, name) {
    return post(`/admin/api/meals/${meal_id}/copies`, { name });
}

function updateIngredientPositions(meal_id, formData) {
    return post(`/admin/api/meals/${meal_id}/organise-ingredients`, {
        ingredients: formData,
    });
}

function addMealNote(meal_id, formData) {
    return post(`/admin/api/meals/${meal_id}/notes`, formData);
}

export {
    deleteMeal,
    copyMeal,
    fetchAllMeals,
    findMealById,
    createNewMeal,
    updateMealInfo,
    updateMealNutritionalInfo,
    updateMealIngredients,
    updateMealInstructions,
    updateMealPublicRecipeNotes,
    updateIngredientPositions,
    addMealNote,
};
