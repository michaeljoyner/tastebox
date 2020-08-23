<template>
    <div class="w-full">
        <div>
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
                <span class="text-gray-700 font-bold text-sm">City</span>
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.city"
                    @input="$emit('input', address)"
                />
            </label>
            <label class="mb-4 block">
                <span class="text-gray-700 font-bold text-sm">Postal Code</span>
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.postal_code"
                    @input="$emit('input', address)"
                />
            </label>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["value"],

    data() {
        return {
            address: {
                line_one: "",
                line_two: "",
                city: "",
                postal_code: "",
            },
        };
    },
};
</script>
