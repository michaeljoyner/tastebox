<template>
    <div>
        <h1 class="type-h1 text-center my-20">My Basket</h1>

        <div v-show="!kits.length">
            <p class="max-w-xl mx-auto">
                You don't have any kits added to your basket yet. Get started by
                checking out our
                <a href="/build-a-box" class="text-green-700 hover:underline"
                    >menus.</a
                >
            </p>
        </div>

        <div class="px-6">
            <div
                v-for="kit in kits"
                class="max-w-2xl mx-auto my-12 p-6 shadow relative"
            >
                <div
                    class="flex justify-between border-b border-gray-200 items-center"
                >
                    <p class="type-h2">
                        {{ kit.name }}
                        <span class="text-green-700 text-base"
                            >R{{ kit.price }}</span
                        >
                    </p>
                    <delete-kit
                        @deleted="updateKits"
                        :kit-id="kit.id"
                        :kit-name="kit.name"
                    ></delete-kit>
                </div>

                <p class="mb-3 mt-1 text-sm text-gray-600 font-normal type-b3">
                    {{ kit.meals_count }} meals ({{ kit.servings_count }}
                    servings)
                </p>

                <p
                    class="mb-4 -ml-2 type-b1 bg-green-100 rounded-full px-4 py-1 text-xs md:text-sm inline-block"
                >
                    For delivery on
                    <span class="font-semibold">{{ kit.delivery_date }}</span>
                </p>

                <p class="type-h3 underline">
                    Meals
                    <a
                        :href="`/build-a-box?kit=${kit.id}`"
                        class="type-b3 text-gray-500 hover:text-green-600"
                        >(edit)</a
                    >
                </p>
                <ul class="">
                    <li
                        v-for="meal in kit.meals"
                        :key="meal.id"
                        class="mb-1 type-b3"
                    >
                        {{ meal.name }} ({{ meal.servings }}
                        {{ meal.servings === 1 ? "person" : "people" }})
                    </li>
                </ul>

                <KitDeliveryAddress
                    :kit="kit"
                    :suggested-addresses="suggested_addresses"
                    :available-areas="available_delivery_areas"
                    :one-of-many="has_many_unset"
                    @updated="onAddressUpdated"
                />

                <div
                    v-if="!kit.eligible_for_order"
                    class="my-6 pl-12 pr-6 py-4 rounded-lg border border-red-600 text-red-700 relative"
                >
                    <warning-icon
                        class="h-5 text-red-600 absolute top-0 left-0 m-2"
                    ></warning-icon>
                    <span class="text-sm">
                        This kit does not contain enough meals or servings to
                        make it eligible for order. It does not count towards
                        the basket total price. You can
                        <a
                            class="font-bold hover:underline"
                            :href="`/build-a-box?kit=${kit.id}`"
                            >add more meals</a
                        >
                        or it will be abandoned on checkout.
                    </span>
                </div>
                <div class="mt-4 md:m-4 static md:absolute top-0 right-0"></div>
            </div>
        </div>

        <div
            v-if="!delivery_all_set"
            class="w-9/12 my-20 max-w-md mx-auto border border-blue-700 rounded-lg bg-blue-100 p-4 text-sm"
        >
            <p>
                Please set a delivery address for each of your kits before
                proceeding to checkout. Thanks.
            </p>

            <p class="mt-6">
                Note: We currently ONLY deliver in Pietermaritzburg and
                surrounding areas, including Nottingham Road, Kloof and
                Pinetown. If you are unsure if you will receive your delivery,
                please contact us before you place your order.
            </p>
        </div>

        <div class="my-20 text-center" v-show="kits.length && delivery_all_set">
            <div
                class="max-w-xl w-9/12 mx-auto mb-10 border border-blue-700 bg-blue-100 p-4 rounded-lg text-sm flex space-x-2 items-center flex-col md:flex-row space-y-4 md:space-y-0"
            >
                <TruckIcon class="text-blue-700 w-5 h-5" />
                <p>
                    Please check your delivery details are correct before
                    proceeding.
                </p>
            </div>
            <span class="text-lg text-gray-600 mr-6 mt-2 hidden sm:inline"
                >Look good?
            </span>
            <a href="/checkout" class="green-btn whitespace-nowrap mt-2"
                >Proceed to Checkout</a
            >
        </div>
    </div>
</template>

<script type="text/babel">
import DeleteKit from "./DeleteKit.vue";
import WarningIcon from "../UI/Icons/WarningIcon.vue";
import KitDeliveryAddress from "./KitDeliveryAddress.vue";
import { eventHub } from "../../../libs/eventHub.js";
import TruckIcon from "../Icons/TruckIcon.vue";

export default {
    components: {
        TruckIcon,
        DeleteKit,
        WarningIcon,
        KitDeliveryAddress,
    },

    props: ["initial-basket"],

    data() {
        return {
            kits: [],
            suggested_addresses: [],
            available_delivery_areas: [],
        };
    },

    computed: {
        delivery_all_set() {
            return this.kits.every((k) => k.can_deliver);
        },

        has_many_unset() {
            return this.kits.filter((k) => !k.can_deliver).length > 1;
        },
    },

    mounted() {
        this.kits = this.initialBasket.kits;
        this.suggested_addresses = this.initialBasket.suggested_addresses;
        this.available_delivery_areas =
            this.initialBasket.available_delivery_areas;
    },

    methods: {
        updateKits(kits) {
            this.kits = kits;
            eventHub.$emit("basket-updated");
        },

        onAddressUpdated({ kits, suggested_addresses }) {
            this.kits = kits;
            this.suggested_addresses = suggested_addresses;
            eventHub.$emit("basket-updated");
        },
    },
};
</script>
