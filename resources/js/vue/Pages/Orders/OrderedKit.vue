<template>
    <page v-if="kit">
        <page-header title="Ordered Kit"></page-header>

        <div class="my-12">
            <div class="divide-x space-x-4">
                <span class="text-xl font-semibold">{{
                    kit.customer_name
                }}</span>
                <span class="text-xl font-semibold pl-4"
                    >MENU #{{ kit.menu_week }}</span
                >
            </div>

            <p class="my-2 text-gray-500">{{ kit.address }}</p>
            <div>
                <colour-label
                    colour="green"
                    :text="`Delivery on: ${kit.delivery_date}`"
                ></colour-label>
            </div>
        </div>

        <div class="my-12">
            <p class="text-xs uppercase text-gray-500 mb-3">Ordered Meals</p>
            <div
                v-for="meal in kit.meals"
                class="my-2 border-b border-gray-200 pb-2"
            >
                <span>{{ meal.servings }} x </span>
                <span>{{ meal.name }}</span>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { computed, onMounted } from "vue";
import { showError } from "../../../libs/notifications";
import ColourLabel from "../../Components/UI/ColourLabel";
export default {
    components: { ColourLabel, Page, PageHeader },

    setup() {
        const store = useStore();
        const route = useRoute();

        const kit = computed(() =>
            store.getters["orders/kitById"](route.params.kit)
        );

        onMounted(() => {
            store
                .dispatch("orders/fetchKits")
                .catch(() => showError("unable to fetch kits"));
        });

        return { kit };
    },
};
</script>
