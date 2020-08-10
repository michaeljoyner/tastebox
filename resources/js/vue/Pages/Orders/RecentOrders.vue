<template>
    <page>
        <page-header title="Recent Orders"></page-header>

        <div v-for="(orders, week) in recent_orders" :key="week">
            <p class="text-lg font-bold mb-4">{{ week }}</p>
            <table class="w-full my-8 border">
                <thead>
                    <tr class="bg-teal-100">
                        <th class="font-normal text-left text-sm uppercase p-2">
                            Date
                        </th>
                        <th class="font-normal text-left text-sm uppercase p-2">
                            Name
                        </th>
                        <th class="font-normal text-left text-sm uppercase p-2">
                            Price
                        </th>
                        <th class="font-normal text-left text-sm uppercase p-2">
                            No. Kits
                        </th>
                        <th class="font-normal text-left text-sm uppercase p-2">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(order, index) in orders"
                        :key="order.id"
                        :class="{ 'bg-gray-100': index % 2 === 1 }"
                    >
                        <td class="px-2 py-1 text-sm">{{ order.date }}</td>
                        <td class="px-2 py-1 text-sm">
                            <router-link :to="`/recent-orders/${order.id}`">{{
                                order.customer_fullname
                            }}</router-link>
                        </td>
                        <td class="px-2 py-1 text-sm">{{ order.price }}</td>
                        <td class="px-2 py-1 text-sm">
                            {{ order.number_of_kits }}
                        </td>
                        <td class="px-2 py-2 text-sm">
                            <order-status :status="order.status"></order-status>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </page>
</template>

<script type="text/babel">
import { showError } from "../../../libs/notifications";
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import OrderStatus from "../../Components/Orders/OrderStatus";

export default {
    components: {
        Page,
        PageHeader,
        OrderStatus,
    },

    computed: {
        recent_orders() {
            return this.$store.state.orders.recent_orders;
        },
    },

    mounted() {
        this.$store
            .dispatch("orders/fetchRecent")
            .catch(() => showError("Failed to fetch recent orders"));
    },
};
</script>
