import AppShell from "./vue/AppShell";

require("./bootstrap");

import { createApp } from "vue";
import { createRouter, createWebHashHistory } from "vue-router";

import { store } from "./stores/index";
import routes from "./routes/admin";

import Navbar from "./vue/Components/Navbar";
import NotificationHub from "./vue/Components/NotificationHub";

const router = createRouter({
    routes,
    history: createWebHashHistory(),
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { x: 0, y: 0 };
        }
    },
});
const app = createApp(AppShell);

app.use(store);
app.use(router);

app.mount("#app");
