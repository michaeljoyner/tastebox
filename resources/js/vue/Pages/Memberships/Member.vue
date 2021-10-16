<template>
    <page v-if="member">
        <page-header :title="member.full_name"></page-header>

        <div class="my-12">
            <div class="flex items-center space-x-2 mb-2">
                <phone-icon class="text-pink-500 h-5 w-5"></phone-icon>
                <p>{{ member.phone }}</p>
            </div>
            <div class="flex items-center space-x-2 mb-2">
                <email-icon class="h-5 w-5 text-pink-500"></email-icon>
                <p>{{ member.email }}</p>
            </div>
            <div class="flex items-center space-x-2 mb-2">
                <location-icon class="h-5 w-5 text-pink-500"></location-icon>
                <p>{{ member.address }}</p>
            </div>
        </div>

        <div class="my-12">
            <p class="type-h3 text-gray-500 mb-6">Recent Orders</p>
            <p class="my-6 text-gray-500" v-show="member.orders.length === 0">
                {{ member.full_name }} has no recent orders on record.
            </p>

            <div>
                <div
                    v-for="order in member.orders"
                    :key="order.id"
                    class="flex space-x-6 mb-2"
                >
                    <colour-label
                        :colour="order.is_paid ? 'green' : 'yellow'"
                        :text="`R${order.price_in_cents / 100}`"
                    ></colour-label>

                    <router-link
                        :to="`/orders/${order.id}`"
                        class="muted-text-btn"
                        >{{ order.order_date }}</router-link
                    >

                    <span>{{
                        order.ordered_kits.length === 1
                            ? "1 Kit"
                            : `${order.ordered_kits.length} kits`
                    }}</span>

                    <span
                        >{{
                            order.ordered_kits.reduce(
                                (carry, kit) => carry + kit.meal_summary.length,
                                0
                            )
                        }}
                        Meals</span
                    >
                </div>
            </div>
        </div>

        <div class="my-12">
            <div class="pb-1 border-b border-indigo-300 flex justify-between">
                <p class="type-h3 text-gray-500">Available Discounts</p>
                <button class="muted-text-btn" @click="createDiscount">
                    Create Discount
                </button>
            </div>
            <p
                class="my-6 text-gray-500"
                v-show="member.discounts.length === 0"
            >
                There are no available discounts for {{ member.full_name }}
            </p>
            <table class="w-full mt-4">
                <thead class="">
                    <tr class="text-left">
                        <th class="p-2">Value</th>
                        <th class="p-2">Code</th>
                        <th class="p-2">Dates</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="discount in member.discounts"
                        :key="discount.id"
                        class=""
                    >
                        <td class="px-2 py-1">
                            <colour-label
                                colour="green"
                                :text="discount.summary"
                            ></colour-label>
                        </td>
                        <td class="px-2 py-1">
                            <button
                                class="muted-text-btn"
                                @click="editDiscount(discount)"
                            >
                                {{ discount.code }}
                            </button>
                        </td>
                        <td class="px=2 py-1">
                            <p class="text-gray-500 type-b3">
                                {{ discount.valid_dates }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <slide-over-panel
            :show="showDiscountPanel"
            @close="showDiscountPanel = false"
        >
            <p class="type-h3">Create a Discount</p>

            <discount-form
                :discount="selectedDiscount"
                @saved="showDiscountPanel = false"
            ></discount-form>

            <div v-if="selectedDiscount">
                <hr class="border-b border-gray-200 my-6" />
                <p class="type-h3">Delete Discount</p>

                <button
                    type="button"
                    @click="showDeleteDiscountOptions = true"
                    class="muted-text-btn"
                >
                    Delete
                </button>

                <div class="my-6" v-show="showDeleteDiscountOptions">
                    <p class="type-b3">
                        This will remove the discount, and possibly make the
                        customer cry. Do you want to proceed?
                    </p>
                    <div class="mt-4 flex space-x-4">
                        <submit-button
                            :waiting="deleting"
                            @click="deleteDiscount"
                            >Delete it!</submit-button
                        >
                        <button
                            class="muted-text-btn"
                            @click="showDeleteDiscountOptions = false"
                            type="button"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </slide-over-panel>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { computed, onMounted, ref, watch } from "vue";
import PhoneIcon from "../../Components/Icons/PhoneIcon";
import EmailIcon from "../../Components/Icons/EmailIcon";
import LocationIcon from "../../Components/Icons/LocationIcon";
import ColourLabel from "../../Components/UI/ColourLabel";
import SlideOverPanel from "../../Components/UI/SlideOverPanel";
import DiscountForm from "../../Components/MemberShips/DiscountForm";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import SubmitButton from "../../Components/UI/SubmitButton";
export default {
    components: {
        SubmitButton,
        DiscountForm,
        SlideOverPanel,
        ColourLabel,
        LocationIcon,
        EmailIcon,
        PhoneIcon,
        PageHeader,
        Page,
    },

    setup() {
        const store = useStore();
        const route = useRoute();

        const member = computed(() => store.state.members.active_member);

        onMounted(() => {
            store.dispatch("members/fetchActive", route.params.member);
        });

        const showDiscountPanel = ref(false);
        const selectedDiscount = ref(null);

        const editDiscount = (discount) => {
            selectedDiscount.value = discount;
            showDiscountPanel.value = true;
        };

        const createDiscount = () => {
            selectedDiscount.value = null;
            showDiscountPanel.value = true;
        };

        watch(
            () => route.params.member,
            (member_id) => {
                if (member_id) {
                    store.dispatch("members/fetchActive", member_id);
                }
            }
        );

        const showDeleteDiscountOptions = ref(false);

        const [deleting, deleteDiscount] = httpAction(
            () =>
                store.dispatch(
                    "members/deleteDiscount",
                    selectedDiscount.value.id
                ),
            () => {
                showSuccess("Discount removed");
                showDiscountPanel.value = false;
                showDeleteDiscountOptions.value = false;
            },
            () => showError("Failed to delete discount")
        );

        return {
            member,
            showDiscountPanel,
            editDiscount,
            selectedDiscount,
            createDiscount,
            showDeleteDiscountOptions,
            deleting,
            deleteDiscount,
        };
    },
};
</script>
