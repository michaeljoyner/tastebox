import { fetchRecentWeeklyBatchReports } from "../apis/reports";

export default {
    namespaced: true,

    state: {
        weekly_batches: {
            recent: [],
        },
    },

    mutations: {
        setRecentWeeklyBatches(state, data) {
            state.weekly_batches.recent = data;
        },
    },

    getters: {
        weekly_batch_labels: (state) =>
            state.weekly_batches.recent.map((b) => b.week),
        weekly_batch_kits: (state) =>
            state.weekly_batches.recent.map((b) => b.kits),
        weekly_batch_meals: (state) =>
            state.weekly_batches.recent.map((b) => b.meals),
        weekly_batch_servings: (state) =>
            state.weekly_batches.recent.map((b) => b.servings),
    },

    actions: {
        getWeeklyBatchData({ commit }) {
            return fetchRecentWeeklyBatchReports().then((data) =>
                commit("setRecentWeeklyBatches", data)
            );
        },
    },
};
