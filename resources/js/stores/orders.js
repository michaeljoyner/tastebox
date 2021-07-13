import { fetchOrderedKits, fetchRecentOrders } from "../apis/orders";

export default {
    namespaced: true,

    state: {
        recent_orders: [],
        upcoming_kits: [],
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
        setRecentOrders(state, orders) {
            state.recent_orders = orders;
        },

        setUpcomingKits(state, kits) {
            state.upcoming_kits = kits;
        },
    },

    actions: {
        fetchRecent({ commit }) {
            return fetchRecentOrders().then((orders) =>
                commit("setRecentOrders", orders)
            );
        },

        fetchKits({ commit }) {
            return fetchOrderedKits().then((kits) =>
                commit("setUpcomingKits", kits)
            );
        },
    },
};
