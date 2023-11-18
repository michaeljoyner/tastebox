import { del, get, post } from "./http";

function createNewMeal(formData) {
    return post("/admin/api/meals", formData);
}

function deleteMeal(meal_id) {
    return del(`/admin/api/meals/${meal_id}`);
}

function fetchAllMeals(query) {
    return get(`/admin/api/meals?${query}`);
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

function updateMealGalleryPositions(meal_id, image_ids) {
    return post(`/admin/api/meals/${meal_id}/images/positions`, { image_ids });
}

function createIngredient(description) {
    return post("/admin/api/ingredients", { description });
}

function fetchAllClassifications() {
    return get("/admin/api/classifications");
}

function publishMeal(meal_id) {
    return post("/admin/api/published-meals", { meal_id });
}

function retractMeal(meal_id) {
    return del(`/admin/api/published-meals/${meal_id}`);
}

function fetchAllMealsWithUsage() {
    return get("/admin/api/used-meals");
}

function createMealShoppingList(meals) {
    return post("/admin/api/meal-shopping-lists", { meals });
}

function fetchMealShoppingList(list_uuid) {
    return get(`/admin/api/meal-shopping-lists/${list_uuid}`);
}

function addMealCosting(meal_id, formData) {
    return post(`/admin/api/meals/${meal_id}/costings`, formData);
}

function updateMealCosting(costing_id, formData) {
    return post(`/admin/api/costings/${costing_id}`, formData);
}

function deleteMealCosting(costing_id) {
    return del(`/admin/api/costings/${costing_id}`);
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
    updateMealGalleryPositions,
    createIngredient,
    fetchAllClassifications,
    publishMeal,
    retractMeal,
    fetchAllMealsWithUsage,
    createMealShoppingList,
    fetchMealShoppingList,
    addMealCosting,
    updateMealCosting,
    deleteMealCosting,
};
