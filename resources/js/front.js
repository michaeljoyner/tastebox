import axios from "axios";
import Vue from "vue";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import BuildKit from "./vue/Components/Front/BuildKit";
import BasketBar from "./vue/Components/Front/BasketBar";
import CheckOut from "./vue/Components/Front/CheckOut";
import KitBuilder from "./vue/Components/Front/KitBuilder";

Vue.component("kit-builder", KitBuilder);
Vue.component("build-kit", BuildKit);
Vue.component("basket-bar", BasketBar);
Vue.component("check-out", CheckOut);

window.eventHub = new Vue();
window.vue_app = new Vue({ el: "#app" });
