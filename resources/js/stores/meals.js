import { showError } from "../libs/notifications";
import { deleteMeal } from "../apis/meals";

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
        fetchMeals({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/meals")
                    .then(({ data }) => {
                        commit("setMeals", data);
                        resolve();
                    })
                    .catch(() => reject("failed to fetch meals"));
            });
        },

        findById({ getters }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/admin/api/meals/${id}`)
                    .then(({ data }) => resolve(data))
                    .catch(() => reject("Unable to fetch meal info"));
            });
        },

        createMeal({ dispatch }) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/meals")
                    .then(({ data }) => {
                        dispatch("fetchMeals").catch(showError);
                        resolve(data);
                    })
                    .catch(() => reject("failed to create meal"));
            });
        },

        saveMeal({ dispatch }, { id, mealData }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/meals/${id}`, mealData)
                    .then(() => {
                        dispatch("fetchMeals").catch(showError);
                        resolve();
                    })
                    .catch(({ response }) => reject(response));
            });
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
                dispatch("fetchMeals").catch(showError)
            );
        },
    },
};
