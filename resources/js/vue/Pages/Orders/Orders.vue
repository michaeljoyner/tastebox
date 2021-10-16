<template>
    <page>
        <page-header title="Recent Orders"></page-header>

        <div>
            <div class="flex space-x-3 items-center">
                <button
                    :disabled="current_page === 1"
                    class="muted-text-btn"
                    @click="prevPage"
                >
                    &larr; Prev page
                </button>
                <span
                    >Page {{ $store.state.orders.page }} of
                    {{ $store.state.orders.total_pages }}
                </span>
                <button
                    @click="nextPage"
                    :disabled="current_page === total_pages"
                    class="muted-text-btn"
                >
                    Next Page &rarr;
                </button>
            </div>
        </div>

        <table class="w-full my-8 border">
            <thead>
                <tr class="bg-teal-100">
                    <th class="text-left text-sm font-semibold p-2">
                        Date
                    </th>
                    <th class="text-left text-sm font-semibold p-2">
                        Name
                    </th>
                    <th class="text-left text-sm font-semibold p-2">
                        Price
                    </th>
                    <th class="text-left text-sm font-semibold p-2">
                        No. Kits
                    </th>
                    <th class="text-left text-sm font-semibold p-2">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody v-show="!fetching">
                <tr
                    v-for="(order, index) in orders"
                    :key="order.id"
                    :class="{ 'bg-gray-100': index % 2 === 1 }"
                >
                    <td class="px-2 py-1 text-sm">{{ order.date }}</td>
                    <td class="px-2 py-1 text-sm">
                        <router-link :to="`/orders/${order.id}`">{{
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
        <div v-show="fetching" class="flex justify-center">
            <p
                class="animate-pulse px-6 py-1 bg-gradient-to-r from-indigo-500 to-pink-500 rounded-lg text-white font-semibold"
            >
                Please wait...
            </p>
        </div>
    </page>
</template>

<script type="text/babel">
import { showError } from "../../../libs/notifications";
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import OrderStatus from "../../Components/Orders/OrderStatus";
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";

export default {
    components: {
        Page,
        PageHeader,
        OrderStatus,
    },

    setup() {
        const store = useStore();

        const orders = computed(() => store.state.orders.list);
        const current_page = computed(() => store.state.orders.page);
        const total_pages = computed(() => store.state.orders.total_pages);

        const fetching = ref(true);

        const nextPage = () => {
            fetching.value = true;
            store
                .dispatch("orders/nextPage")
                .catch(() => showError("Failed to fetch orders"))
                .then(() => (fetching.value = false));
        };

        const prevPage = () => {
            fetching.value = true;
            store
                .dispatch("orders/prevPage")
                .catch(() => showError("Failed to fetch orders"))
                .then(() => (fetching.value = false));
        };

        onMounted(() => {
            store.dispatch("orders/fetch").then(() => (fetching.value = false));
        });

        return {
            orders,
            current_page,
            total_pages,
            nextPage,
            prevPage,
            fetching,
        };
    },
};
</script>
