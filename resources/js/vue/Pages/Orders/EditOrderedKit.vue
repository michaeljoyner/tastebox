<template>
    <page v-if="kit">
        <page-header title="Change Ordered Kit"></page-header>

        <div class="my-12">
            <p class="max-w-xl text-gray-500">
                This is going to update the meals and/or servings for
                <span class="font-semibold">{{ kit.customer_name }}'s</span>
                ordered kit for delivery on
                <span class="font-semibold">{{ kit.delivery_date }}</span
                >. You cannot change the date/menu, in that case, you would need
                to cancel this kit and place a new order.
            </p>
            <p class="my-4 text-xl font-black">Menu {{ kit.menu_week }}</p>
            <p><strong>Customer: </strong>{{ kit.customer_name }}</p>
            <p><strong>Address: </strong>{{ kit.address }}</p>
            <p><strong>Delivery Date: </strong>{{ kit.delivery_date }}</p>
        </div>

        <div class="my-12">
            <ordered-kit-meals-form :kit="kit" @updated="onFormSaved"></ordered-kit-meals-form>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import {useRoute, useRouter} from "vue-router";
import { computed, onMounted } from "vue";
import OrderedKitMealsForm from "../../Components/Orders/OrderedKitMealsForm.vue";
export default {
    components: { OrderedKitMealsForm, PageHeader, Page },

    setup() {
        const store = useStore();
        const route = useRoute();
        const router = useRouter();

        const kit = computed(() => store.state.kits.active);

        const onFormSaved = () => {
            store.dispatch("kits/refreshActive", route.params.kit);
            router.push(`/ordered-kits/${route.params.kit}/show`);
        }

        onMounted(() => {
            store.dispatch("kits/fetchActive", route.params.kit);
        });

        return { kit, onFormSaved };
    },
};
</script>
