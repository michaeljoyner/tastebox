import axios from "axios";
import "lazysizes";
import { createApp } from "vue";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import BuildKit from "./vue/Components/Front/BuildKit";
import BasketBar from "./vue/Components/Front/BasketBar";
import CheckOut from "./vue/Components/Front/CheckOut";
import KitManager from "./vue/Components/Front/KitManager";
import Modal from "@dymantic/modal";
import BasketPage from "./vue/Components/Front/BasketPage";
import ContactPage from "./vue/Components/Front/ContactPage";

const app = createApp({})
    .component("modal", Modal)
    .component("kit-manager", KitManager)
    .component("build-kit", BuildKit)
    .component("basket-page", BasketPage)
    .component("basket-bar", BasketBar)
    .component("check-out", CheckOut)
    .component("contact-form", ContactPage);

app.mount("#app");

window.addEventListener("load", () => {
    const navTrigger = document.querySelector(".nav-trigger");
    const mainNav = document.querySelector(".main-nav");
    navTrigger.addEventListener("click", () => {
        mainNav.classList.toggle("open");
    });
});
