import { fetchById, fetchUpcomingKits, fetchOrders } from "../apis/orders";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        list: [],
        upcoming_kits: [],
        page: 1,
        total_pages: 1,
        active: null,
    },

    getters: {
        upcomingKitsByWeek: (state) => {
            return state.upcoming_kits.reduce((carry, kit) => {
                if (carry.hasOwnProperty(kit.menu_week)) {
                    carry[kit.menu_week].push(kit);
                    return carry;
                }
                carry[kit.menu_week] = [kit];
                return carry;
            }, {});
        },

        kitById: (state) => (id) =>
            state.upcoming_kits.find((k) => k.id === parseInt(id)),
    },

    mutations: {
        setOrders(state, { meta, data }) {
            state.list = data;
            state.page = meta.current_page;
            state.total_pages = meta.last_page;
        },

        setActive(state, order) {
            state.active = order.data;
        },

        setUpcomingKits(state, kits) {
            state.upcoming_kits = kits;
        },
    },

    actions: {
        fetch({ state, dispatch }) {
            if (state.list.length) {
                return Promise.resolve();
            }

            return dispatch("refresh");
        },

        refresh({ commit, state }) {
            return fetchOrders(state.page)
                .then((orders) => commit("setOrders", orders))
                .catch(() => showError("Failed to fetch orders"));
        },

        nextPage({ state, commit }) {
            if (state.page >= state.total_pages) {
                return Promise.resolve();
            }

            return fetchOrders(state.page + 1).then((orders) =>
                commit("setOrders", orders)
            );
        },

        prevPage({ state, commit }) {
            if (state.page <= 1) {
                return Promise.resolve();
            }

            return fetchOrders(state.page - 1).then((orders) =>
                commit("setOrders", orders)
            );
        },

        fetchActive({ state, commit }, order_id) {
            const in_store = state.list.find(
                (order) => order.id === parseInt(order_id)
            );

            if (in_store) {
                commit("setActive", in_store);
                return Promise.resolve();
            }

            return fetchById(order_id)
                .then((order) => commit("setActive", order))
                .catch(() => showError("Failed to fetch order"));
        },

        fetchKits({ commit }) {
            return fetchUpcomingKits().then((kits) =>
                commit("setUpcomingKits", kits)
            );
        },
    },
};
