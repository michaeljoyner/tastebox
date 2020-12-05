require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";

Vue.use(VueRouter);
Vue.use(Vuex);

Vue.config.ignoredElements = ["trix-editor"];

import me from "./stores/me";
import meals from "./stores/meals";
import notifications from "./stores/notifications";
import menus from "./stores/menus";
import orders from "./stores/orders";
import instagram from "./stores/instagram";

const store = new Vuex.Store({
    modules: {
        me,
        meals,
        notifications,
        menus,
        orders,
        instagram,
    },
});

import routes from "./routes/admin";

import Navbar from "./vue/Components/Navbar";
import NotificationHub from "./vue/Components/NotificationHub";

const router = new VueRouter({
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { x: 0, y: 0 };
        }
    },
});
window.app = new Vue({
    components: {
        Navbar,
        NotificationHub,
    },

    el: "#app",

    router,

    store,

    mixins: [
        {
            methods: {
                showSuccess(message) {
                    this.$store.commit("notifications/addSuccess", message);
                },
            },
        },
    ],
});
