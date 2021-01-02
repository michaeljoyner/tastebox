<template>
    <page v-if="menu">
        <page-header :title="menu_title">
            <router-link
                :to="`/menus/${menu.id}/images`"
                class="text-gray-600 text-sm hover:text-blue-600 mr-4"
                >Get Images</router-link
            >
            <router-link
                :to="`/menus/${menu.id}/edit-meals`"
                class="btn btn-main"
                >Choose Meals</router-link
            >
        </page-header>
        <div>
            <p class="text-lg font-bold">{{ menu.current_range_pretty }}</p>
        </div>
        <div class="my-12">
            <open-for-orders-toggle :menu="menu"></open-for-orders-toggle>
        </div>
        <div class="flex justify-between my-12">
            <div class="flex-1 mr-6">
                <meal-list :meals="menu.meals"></meal-list>
            </div>
            <div class="w-64 ml-6">
                <div class="mb-6">
                    <p class="text-xs uppercase">Orders close on</p>
                    <p class="font-bold">{{ menu.orders_close_on_pretty }}</p>
                </div>
                <div class="mb-6">
                    <p class="text-xs uppercase">Delivery from</p>
                    <p class="font-bold">{{ menu.delivery_from_pretty }}</p>
                </div>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import MealList from "../../Components/Meals/MealList";
import OpenForOrdersToggle from "../../Components/Menu/OpenForOrdersToggle";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        Page,
        PageHeader,
        MealList,
        OpenForOrdersToggle,
    },

    computed: {
        menu() {
            return this.$store.getters["menus/byId"](this.$route.params.id);
        },

        menu_title() {
            return `Menu #${this.menu.week_number}`;
        },
    },

    mounted() {
        this.$store.dispatch("menus/fetchMenus").catch(showError);
    },
};
</script>
