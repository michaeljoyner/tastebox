import { fetchInstagramFeed } from "../apis/instagram";

export default {
    namespaced: true,

    state: {
        auth_url: "",
        feed: [],
    },

    mutations: {
        setFeed(state, { feed, auth_url }) {
            state.feed = feed;
            state.auth_url = auth_url;
        },
    },

    actions: {
        fetch({ commit }) {
            return fetchInstagramFeed().then((feed) => commit("setFeed", feed));
        },
    },
};
