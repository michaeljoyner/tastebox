<template>
    <page>
        <page-header title="Upcoming Menus"></page-header>
        <div class="divide-y divide-gray-400">
            <router-link
                v-for="menu in menus"
                :key="menu.id"
                :to="`/menus/${menu.id}`"
            >
                <div class="px-3 py-1 flex items-center">
                    <span class="font-bold">#{{ menu.week_number }}</span>
                    <span class="ml-4"></span>{{ menu.current_range_pretty }}
                </div>
            </router-link>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { showError } from "../../../libs/notifications";

export default {
    components: {
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
