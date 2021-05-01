import {
    createPost,
    deletePost,
    fetchPosts,
    publishPost,
    retractPost,
    updatePost,
} from "../apis/blog";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        all: [],
    },

    getters: {
        byId: (state) => (id) => state.all.find((p) => p.id === parseInt(id)),
    },

    mutations: {
        setPosts(state, posts) {
            state.all = posts;
        },
    },

    actions: {
        fetch({ state, dispatch }) {
            if (state.all.length) {
                return Promise.resolve();
            }

            dispatch("refresh");
        },

        refresh({ commit }) {
            return fetchPosts()
                .then((posts) => commit("setPosts", posts))
                .catch(() => showError("failed to fetch posts"));
        },

        create({ dispatch }, formData) {
            return createPost(formData).then((post) => {
                dispatch("refresh");
                return post;
            });
        },

        update({ dispatch }, { post_id, formData }) {
            return updatePost(post_id, formData).then(() =>
                dispatch("refresh")
            );
        },

        delete({ dispatch }, post_id) {
            return deletePost(post_id).then(() => dispatch("refresh"));
        },

        publish({ dispatch }, post_id) {
            return publishPost(post_id).then(() => dispatch("refresh"));
        },

        retract({ dispatch }, post_id) {
            return retractPost(post_id).then(() => dispatch("refresh"));
        },
    },
};
