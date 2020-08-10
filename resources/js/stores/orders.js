import { fetchRecentOrders } from "../apis/orders";

export default {
    namespaced: true,

    state: {
        recent_orders: [],
    },

    mutations: {
        setRecentOrders(state, orders) {
            state.recent_orders = orders;
        },
    },

    actions: {
        fetchRecent({ commit }) {
            return fetchRecentOrders().then((orders) =>
                commit("setRecentOrders", orders)
            );
        },
    },
};
