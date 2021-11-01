<template>
    <page>
        <page-header title="Upcoming Menus"></page-header>
        <div class="divide-y divide-gray-400"></div>
        <div>
            <div
                v-for="menu in menus"
                :key="menu.id"
                class="shadow-lg flex flex-col md:flex-row p-6 rounded-xl mb-8"
            >
                <div class="flex flex-col items-center space-y-6">
                    <router-link
                        :to="`/menus/${menu.id}`"
                        class="text-2xl font-bold hover:text-blue-600"
                    >
                        <div
                            class="text-4xl text-white grid place-items-center rounded-full w-16 h-16 bg-gradient-to-r from-indigo-500 to-pink-500 font-black"
                        >
                            {{ menu.week_number }}
                        </div>
                    </router-link>

                    <router-link :to="`/menus/${menu.id}`" class="type-b2">{{
                        menu.current_range_pretty
                    }}</router-link>

                    <colour-label
                        :colour="menu.can_order ? 'green' : 'gray'"
                        :text="menu.can_order ? 'open for order' : 'private'"
                    ></colour-label>
                </div>
                <div class="md:pl-12 pt-8 md:pt-0">
                    <p class="type-b2 text-gray-500 underline font-bold mb-3">
                        Selected Meals:
                    </p>
                    <div class="grid grid-cols-4 place-items-center gap-6">
                        <img
                            v-for="meal in menu.meals"
                            :key="meal.id"
                            :src="meal.title_image['thumb']"
                            class="w-16 h-16 rounded-full object-cover mr-4 inline-block"
                        />
                    </div>
                </div>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { showError } from "../../../libs/notifications";
import ColourLabel from "../../Components/UI/ColourLabel";

export default {
    components: {
        ColourLabel,
        Page,
        PageHeader,
    },

    computed: {
        menus() {
            return this.$store.state.menus.upcoming_menus;
        },
    },

    mounted() {
        this.$store.dispatch("menus/fetchMenus").catch(showError);
    },
};
</script>
