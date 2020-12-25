import { fetchMailingList } from "../apis/mailinglist";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        all: [],
    },

    mutations: {
        setList(state, list) {
            state.all = list;
        },
    },

    actions: {
        fetch({ state, dispatch }) {
            if (state.all.length) {
                return Promise.resolve();
            }
            return dispatch("refresh");
        },

        refresh({ commit }) {
            return fetchMailingList()
                .then((list) => commit("setList", list))
                .catch(() => showError("Failed to fetch the mailing list"));
        },
    },
};
