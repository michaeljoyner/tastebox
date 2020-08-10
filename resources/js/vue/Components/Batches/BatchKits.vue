<template>
    <div>
        <p class="text-xl font-bold mb-8">Kits in this batch</p>
        <div v-for="kit in kits" :key="kit.id" class="m-6 p-4 shadow">
            <div class="flex justify-between">
                <p class="font-bold">{{ kit.customer.name }}</p>
                <p class="text-sm">{{ kit.customer.email }}</p>
                <p class="text-sm">{{ kit.customer.phone }}</p>
            </div>
            <ul class="text-sm my-6">
                <li v-for="meal in kit.meals">
                    {{ meal.servings }} x {{ meal.name }}
                </li>
            </ul>
            <div>
                <p>
                    <strong>Deliver to: </strong
                    >{{ formatAddress(kit.delivery_address) }}
                </p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    computed: {
        kits() {
            return this.$store.getters["menus/current_kits"];
        },
    },

    methods: {
        formatAddress({ line_one, line_two, city }) {
            const street = line_two ? `${line_one}, ${line_two}` : line_one;

            return `${street}, ${city}`;
        },
    },
};
</script>
