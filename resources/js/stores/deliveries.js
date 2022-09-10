import { fetchDeliveryAreas } from "../apis/deliveries";
import { showError } from "../libs/notifications";

export default {
    namespaced: true,

    state: {
        all_areas: [],
    },

    mutations: {
        setAreas(state, areas) {
            state.all_areas = areas;
        },
    },

    actions: {
        fetchAllAreas({ commit }) {
            return fetchDeliveryAreas()
                .then((areas) => commit("setAreas", areas))
                .catch(() => showError("Failed to fetch delivery areas"));
        },
    },
};
