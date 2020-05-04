import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        meals: [],
        ingredients: [
            { id: 1, description: "horsemeat" },
            { id: 2, description: "horses" },
            { id: 3, description: "horsefly" },
            { id: 4, description: "norsemen" },
            { id: 5, description: "meningitis" },
            { id: 6, description: "men and women" },
        ],
    },

    mutations: {
        setMeals(state, meals) {
            state.meals = meals;
        },

        setIngredients(state, ingredients) {
            state.ingredients = ingredients;
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

        findById({}, id) {
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
    },
};
