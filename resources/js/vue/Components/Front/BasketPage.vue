<template>
    <div>
        <h1 class="text-5xl text-center font-bold my-12">My Basket</h1>

        <div class="px-6">
            <div
                v-for="kit in kits"
                class="max-w-2xl mx-auto my-12 p-6 shadow relative"
            >
                <p class="font-bold text-green-700">
                    {{ kit.name }}
                    <span class="mb-3 text-sm text-gray-600 font-normal"
                        >{{ kit.meals_count }} meal ({{
                            kit.servings_count
                        }}
                        servings)</span
                    >
                </p>
                <p class="my-3">
                    Delivery from
                    <strong class="text-gray-700">{{
                        kit.delivery_date
                    }}</strong>
                </p>

                <p class="font-bold">Meals</p>
                <ul class="">
                    <li v-for="meal in kit.meals" :key="meal.id" class="mb-1">
                        {{ meal.name }} ({{ meal.servings }} people)
                    </li>
                </ul>
                <div class="mt-4 md:m-4 static md:absolute top-0 right-0">
                    <a
                        :href="`/build-a-box?kit=${kit.id}`"
                        class="font-bold mr-4 text-green-600 hover:text-green-500"
                        >Go to kit</a
                    >
                    <delete-kit
                        @deleted="updateKits"
                        :kit-id="kit.id"
                        :kit-name="kit.name"
                    ></delete-kit>
                </div>
            </div>
        </div>

        <div class="my-20 text-center">
            <span class="text-lg text-gray-600 mr-6">Look good? </span>
            <a
                href="/checkout"
                class="px-4 py-2 rounded shadow bg-green-600 hover:bg-green-500 text-white font-bold"
                >Proceed to Checkout</a
            >
        </div>
    </div>
</template>

<script type="text/babel">
import DeleteKit from "./DeleteKit";
export default {
    components: {
        DeleteKit,
    },

    props: ["initial-basket"],

    data() {
        return {
            kits: [],
        };
    },

    mounted() {
        this.kits = this.initialBasket.kits;
    },

    methods: {
        updateKits(kits) {
            this.kits = kits;
            eventHub.$emit("basket-updated");
        },
    },
};
</script>
