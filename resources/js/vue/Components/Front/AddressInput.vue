<template>
    <div class="w-full">
        <div
            class="p-1"
            :class="{ 'border-b border-t border-red-500': errorMsg }"
        >
            <p class="text-sm text-red-600" v-show="errorMsg">
                Each address requires a street address (line one), city and
                postal code.
            </p>
            <label class="mb-4 block">
                <span class="text-gray-700 font-bold text-sm"
                    >Street address</span
                >
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.line_one"
                    @input="$emit('input', address)"
                />
            </label>
            <label class="mb-4 block">
                <span class="text-gray-700 font-bold text-sm"
                    >Street address (Line two)</span
                >
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.line_two"
                    @input="$emit('input', address)"
                />
            </label>
            <label class="mb-4 block">
                <span class="text-gray-700 font-bold text-sm">Area</span>
                <select
                    v-model="address.city"
                    @input="$emit('input', address)"
                    class="block w-full border p-2 bg-white"
                >
                    <option
                        v-for="city in locations"
                        :key="city"
                        :value="city"
                        >{{ city }}</option
                    >
                </select>
            </label>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["value", "error-msg"],

    data() {
        return {
            address: {
                line_one: "",
                line_two: "",
                city: "",
                postal_code: "",
            },
            locations: [
                "Camperdown",
                "Cato Ridge",
                "Hillcrest",
                "Hilton",
                "Howick",
                "Kloof",
                "Nottingham Road",
                "Pietermaritzburg",
                "Pinetown",
                "Wartburg",
            ],
        };
    },
};
</script>
