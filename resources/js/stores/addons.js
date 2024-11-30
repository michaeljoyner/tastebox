import { isRecent } from "../libs/time_functions";
import {
    createAddOn,
    createAddOnCategory,
    deleteAddOn,
    deleteAddOnCategory,
    fetchAddOn,
    fetchAddOnCategories,
    fetchAddOnCategory,
    updateAddOn,
    updateAddOnCategory,
} from "../apis/addons";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        categories: [],
        last_fetched: null,
        last_fetched_active: null,
        active: null,
        active_category: null,
        last_fetched_active_category: null,
    },

    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
            state.last_fetched = new Date().getTime();
        },

        setActive(state, addOn) {
            state.active = addOn;
            state.last_fetched_active = new Date().getTime();
        },

        clearActive(state) {
            state.active = null;
            state.last_fetched_active = null;
        },

        setActiveCategory(state, category) {
            state.active_category = category;
            state.last_fetched_active_category = new Date().getTime();
        },

        clearActiveCategory(state) {
            state.active_category = null;
            state.last_fetched_active_category = null;
        },
    },

    actions: {
        fetchCategories({ state, dispatch }) {
            if (state.categories.length && isRecent(state.last_fetched, 5)) {
                return Promise.resolve();
            }

            return dispatch("refreshCategories");
        },

        refreshCategories({ commit }) {
            return fetchAddOnCategories()
                .then(({ data }) => commit("setCategories", data))
                .catch(() => showError("Failed to fetch categories"));
        },

        fetchActive({ state, dispatch }, uuid) {
            if (
                state.active &&
                state.active.uuid === uuid &&
                isRecent(state.last_fetched_active, 5)
            ) {
                return Promise.resolve();
            }

            return dispatch("refreshActive", uuid);
        },

        refreshActive({ commit, state }, uuid) {
            if (state.active && state.active.uuid !== uuid) {
                commit("clearActive");
            }
            return fetchAddOn(uuid)
                .then(({ data }) => commit("setActive", data))
                .catch(() => showError("Failed to fetch add on"));
        },

        fetchActiveCategory({ state, dispatch }, uuid) {
            if (
                state.active_category &&
                (state.active_category.uuid === uuid) &
                    isRecent(state.last_fetched_active_category, 5)
            ) {
                return Promise.resolve();
            }

            return dispatch("refreshActiveCategory", uuid);
        },

        refreshActiveCategory({ commit, state }, uuid) {
            if (state.active_category && state.active_category.uuid !== uuid) {
                commit("clearActiveCategory");
            }

            return fetchAddOnCategory(uuid)
                .then(({ data }) => commit("setActiveCategory", data))
                .catch(() => showError("Failed to fetch category"));
        },

        createCategory({ dispatch }, formData) {
            return createAddOnCategory(formData).then(({ data }) => {
                dispatch("refreshCategories");
                return data;
            });
        },

        updateCategory({ dispatch }, { uuid, formData }) {
            return updateAddOnCategory(uuid, formData).then(({ data }) => {
                dispatch("refreshCategories");
                dispatch("refreshActiveCategory", uuid);
                return data;
            });
        },

        deleteCategory({ dispatch }, uuid) {
            return deleteAddOnCategory(uuid).then(({ data }) => {
                dispatch("refreshCategories");
                return data;
            });
        },

        createAddOn({ dispatch }, { category_uuid, formData }) {
            return createAddOn(category_uuid, formData).then(({ data }) => {
                dispatch("refreshCategories");
                dispatch("refreshActiveCategory", category_uuid);
                return data;
            });
        },

        updateAddOn({ dispatch }, { uuid, formData }) {
            return updateAddOn(uuid, formData).then(({ data }) => {
                dispatch("refreshCategories");
                dispatch("refreshActive", uuid);
                return data;
            });
        },

        deleteAddOn({ dispatch }, uuid) {
            return deleteAddOn(uuid).then(({ data }) => {
                dispatch("refreshCategories");
                return data;
            });
        },
    },
};
