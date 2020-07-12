<template>
    <div
        class="w-screen px-4 h-16 fixed bg-red-500 text-white top-0 left-0 right-0 flex items-center justify-end"
        @mouseleave="showBoxes = false"
    >
        <p class="mr-4">My Basket:</p>
        <p
            class="flex items-center"
            @click="showBoxes = !showBoxes"
            @mouseenter="showBoxes = true"
        >
            <span>{{ basket.total_boxes }} Boxes</span>
            <down-chevron class="text-white h-4 ml-2"></down-chevron>
        </p>
        <div class="absolute bg-red-600 w-64 top-16 right-0" v-show="showBoxes">
            <div
                v-for="box in basket.kits"
                :key="box.kit_id"
                class="text-right p-2"
            >
                <a :href="`/my-kits/${box.kit_id}`">{{
                    boxSummaryText(box)
                }}</a>
            </div>
            <div class="p-2 text-right">
                <a href="/basket">Go to basket</a>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import DownChevron from "../UI/Icons/DownChevron";
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
            return `${box.name} (${box.meals.length} ${meal_word})`;
        },
    },
};
</script>
