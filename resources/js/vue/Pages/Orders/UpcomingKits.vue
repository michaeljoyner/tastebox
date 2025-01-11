<template>
    <page>
        <page-header title="Upcoming Kits"></page-header>

        <div class="my-12">
            <div v-for="(batch, week) in menus" :key="week">
                <p class="font-bold text-xl">
                    Menu #{{ week }} ({{ batch.length }} kits)
                </p>
                <p class="text-gray-500 mb-4">{{ batch[0].delivery_date }}</p>

                <table class="w-full mb-8">
                    <thead>
                        <tr class="text-left text-sm">
                            <th class="p-2">Status</th>
                            <th class="p-2">Customer</th>
                            <th class="p-2">Address</th>
                            <th class="p-2">Number of Meals</th>
                            <th class="p-2">AddOns</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr v-for="kit in batch" :key="kit.id" class="relative">
                            <td>
                                <ColourLabel :colour="statusColour(kit.status)" :text="kit.status"></ColourLabel>
                            </td>
                            <td class="p-2">
                                <router-link
                                    class="absolute inset-0"
                                    :to="`/ordered-kits/${kit.id}/show`"
                                    ><span class="sr-only">{{
                                        kit.customer_name
                                    }}</span></router-link
                                >
                                {{ kit.customer_name }}
                            </td>
                            <td class="p-2">{{ kit.address }}</td>
                            <td class="p-2">
                                {{ kit.meals.length }} meals /
                                {{
                                    kit.meals.reduce(
                                        (c, s) => c + s.servings,
                                        0
                                    )
                                }}
                                servings
                            </td>
                            <td class="text-center">{{ kit.add_ons.reduce((carry, ad) => carry + ad.qty, 0)}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="border-b border-gray-300 my-6"></div>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import PageHeader from "../../Components/PageHeader.vue";
import Page from "../../Components/UI/Page.vue";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import { showError } from "../../../libs/notifications.js";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
export default {
    components: {ColourLabel, Page, PageHeader },

    setup() {
        const store = useStore();

        const menus = computed(
            () => store.getters["orders/upcomingKitsByWeek"]
        );

        const statusColour = status => {
            const lookup = {
                due: 'green',
                cancelled: 'red',
                done: 'blue',
            }
            return lookup[status] || 'gray';
        }

        onMounted(() => {
            store
                .dispatch("orders/fetchKits")
                .catch(() => showError("Failed to fetch kits"));
        });

        return { menus, statusColour };
    },
};
</script>
