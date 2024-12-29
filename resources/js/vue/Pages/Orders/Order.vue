<template>
    <page v-if="order">
        <page-header :title="`Order #${order.id}`"></page-header>
        <div class="flex justify-between p-6 shadow">
            <div>
                <p class="font-bold">{{ order.customer.name }}</p>
                <p>{{ order.customer.email }}</p>
                <p>{{ order.customer.phone }}</p>
            </div>
            <div class="text-right">
                <order-status :status="order.status"></order-status>
                <p class="my-2">{{ order.price }}</p>
                <p>{{ order.number_of_kits }} kit(s)</p>
            </div>
        </div>
        <div class="my-12">
            <div
                v-for="(kit, index) in order.kits"
                :key="kit.kit_id"
                class="my-8 shadow relative"
            >
                <div class="p-6">
                    <p class="font-bold mb-3">
                        <router-link :to="`/ordered-kits/${kit.kit_id}/show`"
                            >Kit {{ index + 1 }}</router-link
                        >
                    </p>

                    <div>
                        <p class="font-display font-semibold mb-1">Meals:</p>
                        <div v-for="meal in kit.meals">
                            <p>{{ meal.servings }} x {{ meal.meal }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="font-display font-semibold mb-1">Add-Ons:</p>
                        <div v-for="addon in kit.add_ons">
                            <p>{{ addon.qty }} x {{ addon.name }}</p>
                        </div>
                    </div>
                </div>
                <div class="text-sm bg-gray-100 p-6">
                    <p><strong>For:</strong> {{ kit.delivery_date }}</p>
                    <p>
                        <strong>Deliver to:</strong> {{ kit.delivery_address }}
                    </p>
                </div>
                <ordered-kit-status
                    :status="kit.status"
                    class="absolute top-0 right-0 m-6"
                ></ordered-kit-status>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import OrderStatus from "../../Components/Orders/OrderStatus.vue";
import OrderedKitStatus from "../../Components/Orders/OrderedKitStatus.vue";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import { useRoute } from "vue-router";

export default {
    components: {
        Page,
        PageHeader,
        OrderStatus,
        OrderedKitStatus,
    },

    setup() {
        const store = useStore();
        const route = useRoute();
        const order = computed(() => store.state.orders.active);

        onMounted(() => {
            store.dispatch("orders/fetchActive", route.params.order);
        });

        return { order };
    },
};
</script>
