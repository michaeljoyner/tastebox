import {
    closeMenuForOrders,
    getCurrentBatch,
    openMenuForOrders,
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
            let meal = state.upcoming_menus.find((m) => m.id === parseInt(id));

            if (!meal) {
                meal = state.archived.find((m) => m.id === parseInt(id));
            }
            return meal;
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
    },
};
