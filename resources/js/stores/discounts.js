import {
    createDiscountCode,
    deleteDiscountCode,
    fetchDiscountCodes,
    updateDiscountCode,
} from "../apis/discounts";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        all: [],
    },

    getters: {
        byId: state => id => state.all.find(dc => dc.id === parseInt(id)),
    },

    mutations: {
        setCodes(state, codes) {
            state.all = codes;
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
            return fetchDiscountCodes()
                .then((codes) => commit("setCodes", codes))
                .catch(() => showError("Failed to fetch discount codes"));
        },

        create({ dispatch }, formData) {
            return createDiscountCode(formData).then(() => dispatch("refresh"));
        },

        update({ dispatch }, { code_id, formData }) {
            return updateDiscountCode(code_id, formData).then(() =>
                dispatch("refresh")
            );
        },

        delete({ dispatch }, code_id) {
            return deleteDiscountCode(code_id).then(() => dispatch("refresh"));
        },
    },
};
