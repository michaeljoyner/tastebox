import { showError } from "../libs/notifications";
import {
    addMealNote,
    copyMeal,
    createIngredient,
    createNewMeal,
    deleteMeal,
    fetchAllClassifications,
    fetchAllMeals,
    fetchAllMealsWithUsage,
    findMealById,
    publishMeal,
    retractMeal,
    updateIngredientPositions,
    updateMealGalleryPositions,
    updateMealInfo,
    updateMealIngredients,
    updateMealInstructions,
    updateMealNutritionalInfo,
    updateMealPublicRecipeNotes,
} from "../apis/meals";
import { isRecent } from "../libs/time_functions";

export default {
    namespaced: true,

    state: {
        meals: [],
        used_meals: [],
        page: 1,
        total_pages: null,
        total_items: null,
        ingredients: [],
        classifications: [],
        match_classifications: [],
        search_query: "",
        fetching: false,
        active: null,
        last_fetched_active: null,
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
            return state.used_meals
                .filter((m) => m.total_ordered > 0)
                .sort((a, b) => b.total_servings - a.total_servings);
        },

        searchedClassifications: (state) =>
            state.classifications.filter((c) =>
                state.match_classifications.includes(c.id)
            ),

        isFiltered: (state) =>
            state.search_query.length || state.match_classifications.length,
    },

    mutations: {
        setMeals(state, { data, meta }) {
            state.meals = data;
            state.page = meta.current_page;
            state.total_items = meta.total;
            state.total_pages = meta.last_page;
        },

        setUsedMeals(state, meals) {
            state.used_meals = meals;
        },

        clearMeals(state) {
            state.meals = [];
        },

        setIngredients(state, ingredients) {
            state.ingredients = ingredients;
        },

        setClassifications(state, classifications) {
            state.classifications = classifications;
        },

        setSearchQuery(state, term) {
            state.search_query = term;
        },

        setMatchClassifications(state, classifications) {
            state.match_classifications = classifications;
        },

        startFetching(state) {
            state.fetching = true;
        },

        stopFetching(state) {
            state.fetching = false;
        },

        nextPage(state) {
            if (state.total_pages && state.page < state.total_pages) {
                state.page = state.page + 1;
            }
        },

        prevPage(state) {
            if (state.page > 1) {
                state.page = state.page - 1;
            }
        },

        setActive(state, meal) {
            state.active = meal;
            state.last_fetched_active = new Date().getTime();
        },

        clearActive(state) {
            state.active = null;
        },
    },

    actions: {
        fetchMeals({ dispatch, state }) {
            if (!state.meals || !state.meals.length) {
                return dispatch("refresh");
            }

            return Promise.resolve();
        },

        refresh({ commit, state }) {
            commit("clearMeals");
            let queryParams = new URLSearchParams();
            queryParams.set("page", state.page);
            if (state.search_query) {
                queryParams.set("q", state.search_query);
            }
            if (state.match_classifications) {
                queryParams.set(
                    "classifications",
                    state.match_classifications.join(",")
                );
            }
            commit("startFetching");
            return fetchAllMeals(queryParams.toString())
                .then((meals) => commit("setMeals", meals))
                .catch(() => showError("Failed to fetch meals"))
                .then(() => commit("stopFetching"));
        },

        searchMeals({ commit, dispatch }, { search, classifications }) {
            commit("setSearchQuery", search);
            commit("setMatchClassifications", classifications);

            dispatch("refresh");
        },

        getNextPage({ dispatch, commit }) {
            commit("nextPage");
            return dispatch("refresh");
        },

        getPrevPage({ dispatch, commit }) {
            commit("prevPage");
            return dispatch("refresh");
        },

        fetchActive({ dispatch, state }, meal_id) {
            if (
                state.active?.id === meal_id &&
                isRecent(state.last_fetched_active, 10)
            ) {
                return Promise.resolve();
            }
            return dispatch("refreshActive", meal_id);
        },

        refreshActive({ commit, state, dispatch }, meal_id) {
            if (state.active?.id !== meal_id) {
                commit("clearActive");
            }

            if (state.meals.find((m) => m.id === parseInt(meal_id))) {
                dispatch("refresh");
            }

            return findMealById(meal_id)
                .then(({ data }) => commit("setActive", data))
                .catch(() => showError("Failed to fetch meal"));
        },

        createMeal({ dispatch }, formData) {
            return createNewMeal(formData).then((meal) => {
                dispatch("refresh");
                return meal;
            });
        },

        updateInfo({ dispatch }, { meal_id, formData }) {
            return updateMealInfo(meal_id, formData).then(() =>
                dispatch("refreshActive", meal_id)
            );
        },

        updateNutritionalInfo({ dispatch }, { meal_id, formData }) {
            return updateMealNutritionalInfo(meal_id, formData).then(() =>
                dispatch("refreshActive", meal_id)
            );
        },

        updateIngredients({ dispatch }, { meal_id, ingredients }) {
            return updateMealIngredients(meal_id, ingredients).then(() =>
                dispatch("refreshActive", meal_id)
            );
        },

        updateInstructions({ dispatch }, { meal_id, instructions }) {
            return updateMealInstructions(meal_id, instructions).then(() =>
                dispatch("refreshActive", meal_id)
            );
        },

        updatePublicRecipeNotes(
            { dispatch },
            { meal_id, public_recipe_notes }
        ) {
            return updateMealPublicRecipeNotes(
                meal_id,
                public_recipe_notes
            ).then(() => dispatch("refreshActive", meal_id));
        },

        saveMealGalleryOrder({ dispatch }, { id, image_ids }) {
            return updateMealGalleryPositions(id, image_ids)
                .then(() => dispatch("refreshActive", id))
                .catch(() => showError("Failed to save image order"));
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
            return createIngredient(description)
                .then(({ data }) => {
                    dispatch("fetchIngredients");
                    return data;
                })
                .catch(() => showError("Failed to add ingredient"));
        },

        fetchClassifications({ commit }) {
            return fetchAllClassifications()
                .then((data) => commit("setClassifications", data))
                .catch(() => showError("Unable to fetch meal classifications"));
        },

        publish({ dispatch }, meal_id) {
            return publishMeal(meal_id)
                .then(() => dispatch("refreshActive", meal_id))
                .catch(() => showError("Failed to publish meal"));
        },

        retract({ dispatch }, meal_id) {
            return retractMeal(meal_id)
                .then(() => dispatch("refreshActive", meal_id))
                .catch(() => showError("Failed to publish meal"));
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
                dispatch("refreshActive", meal_id)
            );
        },

        addNote({ dispatch }, { meal_id, formData }) {
            return addMealNote(meal_id, formData).then(() =>
                dispatch("refresh")
            );
        },

        fetchAllUsed({ commit }) {
            return fetchAllMealsWithUsage()
                .then(({ data }) => commit("setUsedMeals", data))
                .catch(() => showError("Failed to fetch meals with usage"));
        },
    },
};
