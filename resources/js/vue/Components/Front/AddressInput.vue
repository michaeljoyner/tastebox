<template>
    <div>
        <div>
            <label>
                <span>Street address</span>
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.line_one"
                    @input="$emit('input', address)"
                />
            </label>
            <label>
                <span>Line two</span>
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.line_two"
                    @input="$emit('input', address)"
                />
            </label>
            <label>
                <span>City</span>
                <input
                    type="text"
                    class="block w-full border p-2"
                    v-model="address.city"
                    @input="$emit('input', address)"
                />
            </label>
            <label>
                <span>Postal Code</span>
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
