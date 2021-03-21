require("./bootstrap");

import { createApp } from "vue";
import { createStore } from "vuex";
import { createRouter } from "vue-router";

import me from "./stores/me";
import meals from "./stores/meals";
import notifications from "./stores/notifications";
import menus from "./stores/menus";
import orders from "./stores/orders";
import instagram from "./stores/instagram";
import discounts from "./stores/discounts";
import mailinglist from "./stores/mailinglist";

const store = createStore({
    modules: {
        me,
        meals,
        notifications,
        menus,
        orders,
        instagram,
        discounts,
        mailinglist,
    },
});

import routes from "./routes/admin";

import Navbar from "./vue/Components/Navbar";
import NotificationHub from "./vue/Components/NotificationHub";

const router = createRouter({
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { x: 0, y: 0 };
        }
    },
});
window.app = createApp({
    components: {
        Navbar,
        NotificationHub,
    },

    el: "#app",
});

app.use(store);
app.use(router);
