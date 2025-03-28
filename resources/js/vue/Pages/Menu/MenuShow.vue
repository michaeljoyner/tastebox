<template>
    <page v-if="menu">
        <page-header :title="menu_title">
            <router-link
                :to="`/menus/${menu.id}/images`"
                class="text-gray-600 text-sm hover:text-blue-600 mr-4"
                >Get Images</router-link
            >
        </page-header>
        <div class="flex space-x-12 my-12">
            <div class="mb-6">
                <p class="text-xs uppercase">Dates Available</p>
                <p class="font-bold">{{ menu.current_range_pretty }}</p>
            </div>
            <div class="mb-6">
                <p class="text-xs uppercase">Orders close on</p>
                <p class="font-bold">{{ menu.orders_close_on_pretty }}</p>
            </div>
            <div class="mb-6">
                <p class="text-xs uppercase">Delivery from</p>
                <p class="font-bold">{{ menu.delivery_from_pretty }}</p>
            </div>
        </div>
        <div class="my-12">
            <open-for-orders-toggle :menu="menu"></open-for-orders-toggle>
        </div>
        <div class="">
            <div
                class="flex justify-between items-center pb-2 mb-6 border-b border-gray-200"
            >
                <p class="text-lg font-bold">Available Meals</p>
                <router-link
                    :to="`/menus/${menu.id}/edit-meals`"
                    class="btn btn-main"
                    >Choose Meals</router-link
                >
            </div>
            <meal-list :meals="menu.meals"></meal-list>
        </div>

        <div class="my-12">
            <div
                class="flex justify-between items-center pb-2 mb-6 border-b border-gray-200"
            >
                <p class="text-lg font-bold">Menu Add-Ons</p>
                <router-link
                    :to="`/menus/${menu.id}/add-ons`"
                    class="btn btn-main"
                    >Set Add-Ons</router-link
                >
            </div>

            <p class="my-6 text-gray-500" v-show="!menu.add_ons.length">
                No add-ons have been added on yet.
            </p>

            <div>
                <div
                    v-for="addon in menu.add_ons"
                    :key="addon.uuid"
                    class="p-3 shadow flex gap-4 items-center"
                >
                    <img
                        :src="addon.image.thumb"
                        class="w-12 h-12 rounded-full object-cover"
                        alt=""
                    />
                    <p class="font-semibold">
                        <RouterLink
                            :to="`/add-ons/${addon.uuid}`"
                            class="hover:text-pink-500"
                        >
                            {{ addon.name }}
                        </RouterLink>
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ addon.price_formatted }}
                    </p>
                </div>
            </div>
        </div>

        <div class="my-12">
            <div
                class="flex justify-between items-center pb-2 mb-6 border-b border-gray-200"
            >
                <p class="text-lg font-bold">Free Recipes</p>
                <router-link
                    :to="`/menus/${menu.id}/free-recipes`"
                    class="btn btn-main"
                    >Choose Recipes</router-link
                >
            </div>
            <meal-list :meals="menu.free_recipe_meals"></meal-list>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import MealList from "../../Components/Meals/MealList.vue";
import OpenForOrdersToggle from "../../Components/Menu/OpenForOrdersToggle.vue";
import { showError } from "../../../libs/notifications.js";

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
