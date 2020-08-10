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

const store = new Vuex.Store({
    modules: {
        me,
        meals,
        notifications,
        menus,
        orders,
    },
});

import routes from "./routes/admin";

import Navbar from "./vue/Components/Navbar";
import NotificationHub from "./vue/Components/NotificationHub";

const router = new VueRouter({ routes });
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
