import {
    createGeneralMemberDiscounts,
    createMemberDiscount,
    deleteGeneralMemberDiscounts,
    deleteMemberDiscount,
    fetchMember,
    fetchMembers,
    updateGeneralMemberDiscounts,
    updateMemberDiscount,
} from "../apis/members";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        current_page: 1,
        total_pages: null,
        list: [],
        active_member: null,
    },

    mutations: {
        setMembers(state, { data, meta }) {
            state.current_page = meta.current_page;
            state.list = data;
            state.total_pages = meta.last_page;
        },

        setActive(state, member) {
            state.active_member = member.data;
        },
    },

    actions: {
        fetch({ state, dispatch }, page = 1) {
            if (state.current_page === page && state.list.length) {
                return Promise.resolve();
            }

            return dispatch("refresh", page);
        },

        refresh({ commit, state }, page) {
            return fetchMembers(page)
                .then((resp) => commit("setMembers", resp))
                .catch(() => showError("Failed to fetch members"));
        },

        fetchActive({ state, commit }, member_id) {
            if (
                state.active_member &&
                state.active_member.id === parseInt(member_id)
            ) {
                return Promise.resolve();
            }

            const in_store = state.list.find(
                (m) => m.id === parseInt(member_id)
            );

            if (in_store) {
                commit("setActive", { data: in_store });
                return Promise.resolve();
            }

            return fetchMember(member_id)
                .then((member) => commit("setActive", member))
                .catch(() => showError("Failed to fetch member"));
        },

        refreshActive({ state, commit, dispatch }) {
            if (!state.active_member) {
                return;
            }
            const in_store = state.list.find(
                (m) => m.id === state.active_member.id
            );
            if (in_store) {
                dispatch("refresh");
            }
            return fetchMember(state.active_member.id)
                .then((member) => commit("setActive", member))
                .catch(() => showError("Failed to fetch member"));
        },

        createDiscount({ state, dispatch }, formData) {
            return createMemberDiscount(state.active_member.id, formData).then(
                () => {
                    dispatch("refreshActive");
                }
            );
        },

        updateDiscount({ state, dispatch }, { discount_id, formData }) {
            return updateMemberDiscount(discount_id, formData).then(() => {
                dispatch("refreshActive");
            });
        },

        deleteDiscount({ dispatch }, discount_id) {
            return deleteMemberDiscount(discount_id).then(() =>
                dispatch("refreshActive")
            );
        },

        createGeneralDiscount({ dispatch }, formData) {
            return createGeneralMemberDiscounts(formData).then(() => {
                dispatch("refreshActive");
                dispatch("discounts/refresh", null, {
                    root: true,
                });
            });
        },

        updateGeneralDiscount({ dispatch }, { discount_tag, formData }) {
            return updateGeneralMemberDiscounts(discount_tag, formData).then(
                () => {
                    dispatch("refreshActive");
                    dispatch("discounts/refresh", null, {
                        root: true,
                    });
                }
            );
        },

        deleteGeneralDiscount({ dispatch }, tag) {
            return deleteGeneralMemberDiscounts(tag).then(() => {
                dispatch("refreshActive");
                dispatch("discounts/refresh", null, {
                    root: true,
                });
            });
        },
    },
};
