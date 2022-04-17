import { fetchActivityLogs } from "../apis/activity-logs";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        all: [],
    },

    getters: {
        latest: (state) => state.all.slice(0, 10),
    },

    mutations: {
        setLogs(state, logs) {
            state.all = logs;
        },
    },

    actions: {
        fetch({ commit }) {
            return fetchActivityLogs()
                .then(({ data }) => commit("setLogs", data))
                .catch(() => showError("Failed to fetch logs"));
        },
    },
};
