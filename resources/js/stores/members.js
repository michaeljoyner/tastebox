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
import { isRecent } from "../libs/time_functions";

export default {
    namespaced: true,

    state: {
        search_term: "",
        page: 1,
        total_pages: null,
        list: [],
        active_member: null,
        last_fetched_active: null,
        fetching: false,
    },

    mutations: {
        setMembers(state, { data, meta }) {
            state.page = meta.current_page;
            state.list = data;
            state.total_pages = meta.last_page;
        },

        setActive(state, member) {
            state.active_member = member.data;
            state.last_fetched_active = new Date().getTime();
        },

        clearActive(state) {
            state.active_member = null;
            state.last_fetched_active = null;
        },

        setFetching(state, isFetching) {
            state.fetching = isFetching;
        },

        nextPage(state) {
            if (state.total_pages && state.total_pages > state.page) {
                state.page = state.page + 1;
            }
        },

        prevPage(state) {
            if (state.page > 1) {
                state.page = state.page - 1;
            }
        },

        resetPage(state) {
            state.page = 1;
        },

        setSearch(state, term) {
            state.search_term = term;
        },
    },

    actions: {
        fetch({ state, dispatch }, page = 1) {
            if (state.current_page === page && state.list.length) {
                return Promise.resolve();
            }

            return dispatch("refresh", page);
        },

        refresh({ commit, state }) {
            commit("setFetching", true);
            return fetchMembers(state.page, state.search_term)
                .then((resp) => commit("setMembers", resp))
                .catch(() => showError("Failed to fetch members"))
                .then(() => commit("setFetching", false));
        },

        fetchNextPage({ commit, dispatch }) {
            commit("nextPage");
            return dispatch("refresh");
        },

        fetchPrevPage({ commit, dispatch }) {
            commit("prevPage");
            return dispatch("refresh");
        },

        reset({ commit, dispatch }) {
            commit("setSearch", "");
            commit("resetPage");
            return dispatch("refresh");
        },

        search({ commit, dispatch }, term) {
            commit("setSearch", term);
            commit("resetPage");
            return dispatch("refresh");
        },

        fetchActive({ state, dispatch }, member_id) {
            if (
                state.active_member &&
                state.active_member.id === parseInt(member_id) &&
                isRecent(state.last_fetched_active, 10)
            ) {
                return Promise.resolve();
            }

            return dispatch("refreshActive", member_id);
        },

        refreshActive({ state, commit, dispatch }, member_id) {
            if (state.list.find((m) => m.id === member_id)) {
                dispatch("refresh");
            }

            if (state.active_member?.id !== member_id) {
                commit("clearActive");
            }

            return fetchMember(member_id)
                .then((member) => commit("setActive", member))
                .catch(() => showError("Failed to fetch member"));
        },

        createDiscount({ state, dispatch }, formData) {
            return createMemberDiscount(state.active_member.id, formData).then(
                () => {
                    dispatch("refreshActive", state.active_member.id);
                }
            );
        },

        updateDiscount({ state, dispatch }, { discount_id, formData }) {
            return updateMemberDiscount(discount_id, formData).then(() => {
                dispatch("refreshActive", state.active_member.id);
            });
        },

        deleteDiscount({ dispatch, state }, discount_id) {
            return deleteMemberDiscount(discount_id).then(() =>
                dispatch("refreshActive", state.active_member.id)
            );
        },

        createGeneralDiscount({ dispatch, state }, formData) {
            return createGeneralMemberDiscounts(formData).then(() => {
                dispatch("refreshActive", state.active_member.id);
                dispatch("discounts/refresh", null, {
                    root: true,
                });
            });
        },

        updateGeneralDiscount({ dispatch, state }, { discount_tag, formData }) {
            return updateGeneralMemberDiscounts(discount_tag, formData).then(
                () => {
                    dispatch("refreshActive", state.active_member.id);
                    dispatch("discounts/refresh", null, {
                        root: true,
                    });
                }
            );
        },

        deleteGeneralDiscount({ dispatch, state }, tag) {
            return deleteGeneralMemberDiscounts(tag).then(() => {
                dispatch("refreshActive", state.active_member.id);
                dispatch("discounts/refresh", null, {
                    root: true,
                });
            });
        },
    },
};
