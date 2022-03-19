import {
    assignMenuFreeRecipes,
    closeMenuForOrders,
    getCurrentBatch,
    openMenuForOrders,
    placeManualOrder,
    setMenuMeals,
} from "../apis/menus";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        upcoming_menus: [],
        archived: [],
        current_batch: null,
    },

    getters: {
        byId: (state) => (id) => {
            let menu = state.upcoming_menus.find((m) => m.id === parseInt(id));

            if (!menu) {
                menu = state.archived.find((m) => m.id === parseInt(id));
            }
            return menu;
        },

        current_kits: (state) =>
            state.current_batch ? state.current_batch.kits : [],
        current_meals: (state) =>
            state.current_batch ? state.current_batch.meals : [],
        current_ingredients: (state) =>
            state.current_batch ? state.current_batch.ingredients : [],
        current_shopping_list: (state) =>
            state.current_batch ? state.current_batch.shopping_list : [],
        current_batch_menu_id: (state) =>
            state.current_batch ? state.current_batch.menu_id : null,
        current_batch_deliver_date: (state) =>
            state.current_batch ? state.current_batch.delivery_date : null,
        currentAvailableMeals: (state) => {
            if (!state.current_batch) {
                return [];
            }
            const menu = state.upcoming_menus.find(
                (m) => m.id === state.current_batch.menu_id
            );
            return menu ? menu.meals : [];
        },
    },

    mutations: {
        setMenus(state, menus) {
            state.upcoming_menus = menus;
        },

        setCurrentBatch(state, batch) {
            state.current_batch = batch;
        },
    },

    actions: {
        fetchMenus({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/upcoming-menus")
                    .then(({ data }) => {
                        commit("setMenus", data);
                        resolve();
                    })
                    .catch(() => reject("Unable to fetch upcoming menus"));
            });
        },

        saveMenuMeals({}, { menu_id, meal_ids }) {
            return setMenuMeals(menu_id, meal_ids);
        },

        openForOrders({ dispatch }, menu_id) {
            return openMenuForOrders(menu_id).then(() =>
                dispatch("fetchMenus").catch(() =>
                    showError("Unable to fetch current menus.")
                )
            );
        },

        closeForOrders({ dispatch }, menu_id) {
            return closeMenuForOrders(menu_id).then(() =>
                dispatch("fetchMenus").catch(() =>
                    showError("Unable to fetch current menus.")
                )
            );
        },

        fetchCurrentBatch({ commit }) {
            getCurrentBatch().then((batch) => commit("setCurrentBatch", batch));
        },

        manualOrder({ dispatch }, formData) {
            return placeManualOrder(formData).then(() =>
                dispatch("fetchCurrentBatch").catch(() =>
                    showError("unable to refresh batch")
                )
            );
        },

        assignFreeRecipes({ dispatch }, { menu_id, meal_ids }) {
            return assignMenuFreeRecipes(menu_id, meal_ids).then(() =>
                dispatch("fetchMenus").catch(() =>
                    showError("Unable to fetch current menus.")
                )
            );
        },
    },
};
