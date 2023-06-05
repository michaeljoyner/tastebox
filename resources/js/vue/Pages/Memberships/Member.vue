<template>
    <page v-if="member">
        <page-header :title="member.full_name"></page-header>

        <div
            class="my-12 bg-slate-900 text-white px-6 py-2 rounded-full flex items-center justify-between"
        >
            <div class="flex items-center space-x-4">
                <ShieldIcon class="h-6 w-6 text-green-500" />
                <p class="font-semibold">Signed up {{ member.signed_up }}</p>
            </div>

            <p class="flex items-center space-x-2">
                <span>Profile info:</span>
                <CheckIcon
                    class="w-6 h-6 text-blue-500"
                    v-show="member.profile_complete"
                />
                <EcksIcon
                    class="w-6 h-6 text-red-500"
                    v-show="!member.profile_complete"
                />
            </p>
        </div>

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
                    v-for="order in member.orders.slice(0, 5)"
                    :key="order.id"
                    class="flex items-center space-x-6 mb-2"
                >
                    <colour-label
                        :colour="order.is_paid ? 'green' : 'yellow'"
                        :text="`${order.price}`"
                    ></colour-label>

                    <router-link
                        :to="`/orders/${order.id}`"
                        class="muted-text-btn"
                        >{{ order.date }}</router-link
                    >

                    <span>{{
                        order.kits.length === 1
                            ? "1 Kit"
                            : `${order.kits.length} kits`
                    }}</span>

                    <span
                        >{{
                            order.kits.reduce(
                                (carry, kit) => carry + kit.meals.length,
                                0
                            )
                        }}
                        Meals</span
                    >
                </div>
            </div>
            <div class="text-gray-500 italic my-4">
                {{ member.orders.length }} orders on record
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
            <p v-if="selectedDiscount" class="type-h3">Edit this Discount</p>
            <p v-else class="type-h3">Create a Discount</p>

            <p
                class="my-6 text-gray-600"
                v-if="selectedDiscount && selectedDiscount.discount_tag"
            >
                This discount was created for all members to use and cannot be
                edited on as per person basis. If you wish to edit the discount
                for all members,
                <router-link
                    :to="`/discount-codes`"
                    class="text-blue-500 hover:underline"
                    >head over here</router-link
                >
            </p>

            <discount-form
                v-show="!selectedDiscount || !selectedDiscount.discount_tag"
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
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { computed, onMounted, ref, watch } from "vue";
import PhoneIcon from "../../Components/Icons/PhoneIcon.vue";
import EmailIcon from "../../Components/Icons/EmailIcon.vue";
import LocationIcon from "../../Components/Icons/LocationIcon.vue";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import SlideOverPanel from "../../Components/UI/SlideOverPanel.vue";
import DiscountForm from "../../Components/MemberShips/DiscountForm.vue";
import { httpAction } from "../../../libs/httpAction.js";
import { showError, showSuccess } from "../../../libs/notifications.js";
import SubmitButton from "../../Components/UI/SubmitButton.vue";
import ShieldIcon from "../../Components/Icons/ShieldIcon.vue";
import CheckIcon from "../../Components/Icons/CheckIcon.vue";
import EcksIcon from "../../Components/Icons/EcksIcon.vue";
export default {
    components: {
        EcksIcon,
        CheckIcon,
        ShieldIcon,
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
