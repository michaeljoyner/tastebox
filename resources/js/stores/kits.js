import { fetchKitById, fetchOrderedKits } from "../apis/kits";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        list: [],
        current_page: 1,
        total_pages: 1,
        active: null,
    },

    mutations: {
        setKits(state, { meta, data }) {
            state.current_page = meta.current_page;
            state.total_pages = meta.last_page;
            state.list = data;
        },

        nextPage(state) {
            if (state.current_page < state.total_pages) {
                state.current_page = state.current_page + 1;
            }
        },

        prevPage(state) {
            if (state.current_page > 1) {
                state.current_page = state.current_page - 1;
            }
        },

        setActive(state, kit) {
            state.active = kit;
        },
    },

    actions: {
        fetch({ state, dispatch }) {
            if (state.list.length) {
                return Promise.resolve();
            }

            return dispatch("refresh");
        },

        refresh({ state, commit }) {
            return fetchOrderedKits(state.current_page)
                .then((data) => commit("setKits", data))
                .catch(() => showError("Failed to fetch kits"));
        },

        fetchActive({ state, dispatch }, kit_id) {
            if (state.active && state.active.id === kit_id) {
                return Promise.resolve();
            }

            return dispatch("refreshActive", kit_id);
        },

        refreshActive({ commit, state }, kit_id) {
            if (!kit_id && !state.active) {
                throw new Error("cannot refresh kit without id");
            }

            if (!kit_id) {
                kit_id = state.active.id;
            }

            return fetchKitById(kit_id)
                .then(({ data }) => commit("setActive", data))
                .catch(() => showError("Failed to fetch kit"));
        },
    },
};
