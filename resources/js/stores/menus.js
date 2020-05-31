export default {
    namespaced: true,

    state: {
        upcoming_menus: [],
        archived: [],
    },

    getters: {
        byId: (state) => (id) => {
            let meal = state.upcoming_menus.find((m) => m.id === parseInt(id));

            if (!meal) {
                meal = state.archived.find((m) => m.id === parseInt(id));
            }
            return meal;
        },
    },

    mutations: {
        setMenus(state, menus) {
            state.upcoming_menus = menus;
        },
    },

    actions: {
        fetchMenus({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/upcoming-menus")
                    .then(({ data }) => {
                        commit("setMenus", data);
                        resolve();
                    })
                    .catch(() => reject("Unable to fetch upcoming menus"));
            });
        },
    },
};
