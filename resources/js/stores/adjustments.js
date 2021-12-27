import {
    fetchAdjustmentById,
    fetchAdjustments,
    fetchUnresolvedAdjustments,
    resolveAdjustment,
} from "../apis/adjustments";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        list: [],
        unresolved: [],
        page: 1,
        total_pages: 1,
        active: null,
    },

    mutations: {
        setAdjustments(state, { meta, data }) {
            state.list = data;
            state.page = meta.current_page;
            state.total_pages = meta.last_page;
        },

        setUnresolved(state, adjustments) {
            state.unresolved = adjustments;
        },

        setActive(state, adjustment) {
            state.active = adjustment;
        },

        nextPage(state) {
            if (state.page < state.total_pages) {
                state.page = state.page + 1;
            }
        },

        prevPage(state) {
            if (state.page > 1) {
                state.page = state.page - 1;
            }
        },

        setPage(state, page) {
            state.page = page;
        },
    },

    actions: {
        fetch({ state, commit, dispatch }, page = 1) {
            if (state.list.length && state.page === page) {
                return Promise.resolve();
            }
            commit("setPage", page);
            return dispatch("refresh");
        },

        fetchUnresolved({ state, dispatch }) {
            if (state.unresolved.length) {
                return Promise.resolve();
            }

            dispatch("refresh");
        },

        refresh({ commit, state }) {
            fetchUnresolvedAdjustments()
                .then(({ data }) => commit("setUnresolved", data))
                .catch(() =>
                    showError("Failed to fetch unresolved adjustments")
                );
            return fetchAdjustments(state.page)
                .then((adjustments) => commit("setAdjustments", adjustments))
                .catch(() => showError("Failed to fetch adjustments"));
        },

        fetchById({ state, commit }, adjustment_id) {
            if (state.active && state.active.id === parseInt(adjustment_id)) {
                return Promise.resolve();
            }

            return fetchAdjustmentById(adjustment_id)
                .then(({ data }) => commit("setActive", data))
                .catch(() => showError("Failed to fetch active adjustment"));
        },

        refreshActive({ state, commit, dispatch }) {
            if (state.list.find((a) => a.id === state.active?.id)) {
                dispatch("refresh");
            }
            return fetchAdjustmentById(state.active.id)
                .then(({ data }) => commit("setActive", data))
                .catch(() => showError("Failed to fetch active adjustment"));
        },

        resolve({ dispatch }, { adjustment_id, note }) {
            return resolveAdjustment(adjustment_id, note).then(() =>
                dispatch("refreshActive")
            );
        },
    },
};
