<template>
    <page>
        <page-header title="Upcoming Menus"></page-header>
        <div class="divide-y divide-gray-400"></div>
        <div>
            <table class="w-full">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="p-2 text-left font-normal uppercase">#</th>
                        <th class="p-2 text-left font-normal uppercase">
                            Dates
                        </th>
                        <th class="p-2 text-left font-normal uppercase">
                            Meals
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="menu in menus" :key="menu.id">
                        <td class="px-2 pb-2">
                            <router-link
                                :to="`/menus/${menu.id}`"
                                class="text-2xl font-bold hover:text-blue-600"
                            >
                                {{ menu.week_number }}
                            </router-link>
                        </td>
                        <td class="px-2 pb-2">
                            <router-link :to="`/menus/${menu.id}`">{{
                                menu.current_range_pretty
                            }}</router-link>
                        </td>
                        <td class="px-2 pb-2">
                            <div>
                                <img
                                    v-for="meal in menu.meals"
                                    :key="meal.id"
                                    :src="meal.title_image['thumb']"
                                    class="w-12 mr-4 inline-block"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
