import { showError } from "../libs/notifications";
import {
    copyMeal,
    createNewMeal,
    deleteMeal,
    fetchAllMeals,
    updateIngredientPositions,
    updateMealInfo,
    updateMealIngredients,
    updateMealInstructions,
    updateMealNutritionalInfo,
} from "../apis/meals";

export default {
    namespaced: true,

    state: {
        meals: [],
        ingredients: [],
        classifications: [],
    },

    getters: {
        byId: (state) => (id) =>
            state.meals.find((meal) => meal.id === parseInt(id)),

        recent: (state) =>
            state.meals
                .sort(
                    (a, b) =>
                        b.last_touched_timestamp - a.last_touched_timestamp
                )
                .slice(0, 5),

        byPopularity: (state) => {
            return state.meals
                .filter((m) => m.total_ordered > 0)
                .sort((a, b) => b.total_servings - a.total_servings);
        },
    },

    mutations: {
        setMeals(state, meals) {
            state.meals = meals;
        },

        setIngredients(state, ingredients) {
            state.ingredients = ingredients;
        },

        setClassifications(state, classifications) {
            state.classifications = classifications;
        },
    },

    actions: {
        fetchMeals({ dispatch, state }) {
            if (!state.meals || !state.meals.length) {
                return dispatch("refresh");
            }

            return Promise.resolve();
        },

        refresh({ commit }) {
            return fetchAllMeals()
                .then((meals) => commit("setMeals", meals))
                .catch(() => showError("Failed to fetch meals"));
        },

        createMeal({ dispatch }, formData) {
            return createNewMeal(formData).then((meal) => {
                dispatch("refresh");
                return meal;
            });
        },

        updateInfo({ dispatch }, { meal_id, formData }) {
            return updateMealInfo(meal_id, formData).then(() =>
                dispatch("refresh")
            );
        },

        updateNutritionalInfo({ dispatch }, { meal_id, formData }) {
            return updateMealNutritionalInfo(meal_id, formData).then(() =>
                dispatch("refresh")
            );
        },

        updateIngredients({ dispatch }, { meal_id, ingredients }) {
            return updateMealIngredients(meal_id, ingredients).then(() =>
                dispatch("refresh")
            );
        },

        updateInstructions({ dispatch }, { meal_id, instructions }) {
            return updateMealInstructions(meal_id, instructions).then(() =>
                dispatch("refresh")
            );
        },

        saveMealGalleryOrder({ dispatch }, { id, image_ids }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/meals/${id}/images/positions`, {
                        image_ids,
                    })
                    .then(() => {
                        dispatch("fetchMeals");
                        resolve();
                    })
                    .catch(() => reject("Failed to save image order"));
            });
        },

        fetchIngredients({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/ingredients")
                    .then(({ data }) => commit("setIngredients", data))
                    .catch(() => reject("error fetching ingredients"));
            });
        },

        addIngredient({ dispatch }, description) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/ingredients", { description })
                    .then(({ data }) => {
                        dispatch("fetchIngredients");
                        resolve(data);
                    })
                    .catch(reject);
            });
        },

        fetchClassifications({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/classifications")
                    .then(({ data }) => commit("setClassifications", data))
                    .catch(() =>
                        reject("Unable to fetch meal classifications")
                    );
            });
        },

        publishMeal({ dispatch }, meal_id) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/published-meals", { meal_id })
                    .then(() => {
                        dispatch("fetchMeals").catch(showError);
                        resolve();
                    })
                    .catch(() => reject("Unable to publish meal."));
            });
        },

        retractMeal({ dispatch }, meal_id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/published-meals/${meal_id}`)
                    .then(() => {
                        dispatch("fetchMeals").catch(showError);
                        resolve();
                    })
                    .catch(() => reject("Unable to retract meal."));
            });
        },

        deleteMealById({ dispatch }, meal_id) {
            return deleteMeal(meal_id).then(() =>
                dispatch("refresh").catch(showError)
            );
        },

        copy({ dispatch }, { meal_id, name }) {
            return copyMeal(meal_id, name).then(() => dispatch("refresh"));
        },

        organiseIngredients({ dispatch }, { meal_id, formData }) {
            return updateIngredientPositions(meal_id, formData).then(() =>
                dispatch("refresh")
            );
        },
    },
};
