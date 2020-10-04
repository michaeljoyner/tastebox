import axios from "axios";
import Vue from "vue";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import BuildKit from "./vue/Components/Front/BuildKit";
import BasketBar from "./vue/Components/Front/BasketBar";
import CheckOut from "./vue/Components/Front/CheckOut";
import KitManager from "./vue/Components/Front/KitManager";
import Modal from "@dymantic/modal";
import BasketPage from "./vue/Components/Front/BasketPage";
import ContactPage from "./vue/Components/Front/ContactPage";

Vue.component("modal", Modal);
Vue.component("kit-manager", KitManager);
Vue.component("build-kit", BuildKit);
Vue.component("basket-page", BasketPage);
Vue.component("basket-bar", BasketBar);
Vue.component("check-out", CheckOut);
Vue.component("contact-form", ContactPage);

window.eventHub = new Vue();
window.vue_app = new Vue({ el: "#app" });

window.addEventListener("load", () => {
    const navTrigger = document.querySelector(".nav-trigger");
    const mainNav = document.querySelector(".main-nav");
    navTrigger.addEventListener("click", () => {
        mainNav.classList.toggle("open");
    });
});
