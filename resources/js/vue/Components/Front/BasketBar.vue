<template>
    <div
        class="flex items-center text-gray-700"
        @click="showBoxes = !showBoxes"
        @mouseenter="showBoxes = true"
    >
        <div
            class="mx-4"
            :class="{ 'text-gray-700': has_kits, 'text-gray-400': !has_kits }"
        >
            <svg
                class="h-6 fill-current"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                width="24"
                height="24"
            >
                <path
                    class="heroicon-ui"
                    d="M17 16a3 3 0 1 1-2.83 2H9.83a3 3 0 1 1-5.62-.1A3 3 0 0 1 5 12V4H3a1 1 0 1 1 0-2h3a1 1 0 0 1 1 1v1h14a1 1 0 0 1 .9 1.45l-4 8a1 1 0 0 1-.9.55H5a1 1 0 0 0 0 2h12zM7 12h9.38l3-6H7v6zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"
                />
            </svg>
        </div>
        <p class="flex items-center" v-show="has_kits">
            <span class="font-bold text-green-600">{{
                basket.kits.length
            }}</span>
            <down-chevron class="text-grey-700 h-4 ml-2"></down-chevron>
        </p>
        <div
            class="absolute bg-white shadow-2xl w-64 top-16 right-0 p-6"
            v-show="showBoxes && has_kits"
            @mouseleave="showBoxes = false"
        >
            <div v-show="has_kits">
                <p class="text-sm uppercase pb-2 mb-2 border-b">Your kits:</p>
                <div v-for="box in basket.kits" :key="box.id" class="p-2 mb-2">
                    <a
                        :href="`/build-a-box?kit=${box.id}`"
                        class="hover:text-teal-600"
                    >
                        <span class="font-bold">{{ box.name }}</span>
                        <span class="text-xs text-gray-600 block">{{
                            boxSummaryText(box)
                        }}</span>
                    </a>
                </div>
            </div>

            <div class="p-2">
                <a
                    href="/basket"
                    class="bg-green-600 hover:bg-green-400 text-white rounded-lg px-3 py-1 shadow"
                    >Go to basket</a
                >
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import DownChevron from "../UI/Icons/DownChevron.vue";
import { eventHub } from "../../../libs/eventHub.js";

export default {
    components: {
        DownChevron,
    },

    data() {
        return {
            showBoxes: false,
            basket: {
                total_price: null,
                total_boxes: null,
                kits: [],
            },
        };
    },

    computed: {
        has_kits() {
            return this.basket.kits.length > 0;
        },
    },

    created() {
        eventHub.$on("basket-updated", this.fetchBasketInfo);
    },

    mounted() {
        this.fetchBasketInfo();
    },

    methods: {
        fetchBasketInfo() {
            axios
                .get("/basket-summary")
                .then(({ data }) => (this.basket = data))
                .catch(console.log);
        },

        boxSummaryText(box) {
            const meal_word = box.meals.length === 1 ? "meal" : "meals";
            const add_on_word = box.add_ons.length === 1 ? "add-on" : "add-ons";
            return `(${box.meals.length} ${meal_word} & ${box.add_ons.length} ${add_on_word})`;
        },
    },
};
</script>
