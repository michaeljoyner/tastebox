import axios from "axios";
import "lazysizes";
import { createApp } from "vue";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import BuildKit from "./vue/Components/Front/BuildKit.vue";
import BasketBar from "./vue/Components/Front/BasketBar.vue";
import CheckOut from "./vue/Components/Front/CheckOut.vue";
import KitManager from "./vue/Components/Front/KitManager.vue";
import BasketPage from "./vue/Components/Front/BasketPage.vue";
import ContactPage from "./vue/Components/Front/ContactPage.vue";
import NavMenu from "./vue/Components/Front/NavMenu.vue";
import ToastAlerts from "./vue/Components/Front/ToastAlerts.vue";

const app = createApp({})
    .component("nav-menu", NavMenu)
    .component("kit-manager", KitManager)
    .component("build-kit", BuildKit)
    .component("basket-page", BasketPage)
    .component("basket-bar", BasketBar)
    .component("check-out", CheckOut)
    .component("contact-form", ContactPage)
    .component("toast-alerts", ToastAlerts);

app.mount("#app");

window.addEventListener("DOMContentLoaded", () => {
    const navTrigger = document.querySelector(".nav-trigger");
    const mainNav = document.querySelector(".main-nav");
    navTrigger.addEventListener("click", () => {
        mainNav.classList.toggle("open");
    });

    [...document.querySelectorAll(".scroll-jumper")].forEach((el) => {
        const t = document.getElementById(
            el.getAttribute("data-scroll-target")
        );

        el.addEventListener("click", (ev) => {
            ev.preventDefault();
            t.scrollIntoView({ behavior: "smooth" });
        });
    });
});
