<template>
    <div>
        <div class="flex justify-around">
            <div>
                <p class="text-xl my-12 text-center">Your Order</p>
                <div
                    v-for="kit in basket.kits"
                    :key="kit.kit_id"
                    class="mb-4 w-64"
                >
                    <p class="flex justify-between border-b border-gray-200">
                        <span class="font-bold mr-8">{{ kit.name }}</span>
                        <span>R{{ kit.price }}</span>
                    </p>
                    <p class="text-sm">
                        {{ kit.meals_count }} meals ({{ kit.servings_count }}
                        servings)
                    </p>
                </div>
                <p class="w-64 flex justify-between">
                    <span>Sub-total:</span>
                    <span>R{{ basket.total_price }}</span>
                </p>
                <p class="w-64 flex justify-between">
                    <span>Delivery fee:</span>
                    <span>R50</span>
                </p>
                <p class="w-64 flex justify-between">
                    <span>Total:</span>
                    <span>R{{ basket.total_price + 50 }}</span>
                </p>
            </div>
            <div>
                <p class="text-xl my-12 text-center">Your Details</p>

                <div>
                    <div
                        class="my-4"
                        :class="{
                            'border-b border-red-400': formErrors.first_name,
                        }"
                    >
                        <label class="form-label" for="first_name"
                            >First name</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.first_name"
                            >{{ formErrors.first_name }}</span
                        >
                        <input
                            type="text"
                            name="first_name"
                            v-model="formData.first_name"
                            class="block border p-2 w-64"
                            id="first_name"
                        />
                    </div>
                    <div
                        class="my-4"
                        :class="{
                            'border-b border-red-400': formErrors.last_name,
                        }"
                    >
                        <label class="form-label" for="last_name"
                            >Last name</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.last_name"
                            >{{ formErrors.last_name }}</span
                        >
                        <input
                            type="text"
                            name="last_name"
                            v-model="formData.last_name"
                            class="block border p-2 w-64"
                            id="last_name"
                        />
                    </div>

                    <div
                        class="my-4"
                        :class="{ 'border-b border-red-400': formErrors.email }"
                    >
                        <label class="form-label" for="email">Your Email</label>
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.email"
                            >{{ formErrors.email }}</span
                        >
                        <input
                            type="email"
                            name="email"
                            v-model="formData.email"
                            class="block border p-2 w-64"
                            id="email"
                        />
                    </div>
                    <div
                        class="my-4"
                        :class="{ 'border-b border-red-400': formErrors.phone }"
                    >
                        <label class="form-label" for="phone"
                            >Phone Number</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.phone"
                            >{{ formErrors.phone }}</span
                        >
                        <input
                            type="text"
                            name="phone"
                            v-model="formData.phone"
                            class="block border p-2 w-64"
                            id="phone"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-md">
            <p class="text-xl text-center my-20">Delivery</p>
            <div v-if="use_multiple_addresses === null">
                <p class="mt-8 mb-4">
                    You have ordered more than one box. Would you like to have
                    any of the boxes sent to different address?
                </p>
                <button class="mb-8" @click="use_multiple_addresses = true">
                    Yes Please
                </button>
                <button class="mb-8" @click="use_multiple_addresses = false">
                    no, just the one
                </button>
            </div>

            <div v-if="use_multiple_addresses === true">
                <div v-for="kit in basket.kits" :key="kit.kit_id">
                    <p>{{ kit.name }}</p>
                    <p>Delivery from: {{ kit.delivery_date }}</p>
                    <address-input
                        v-model="formData.delivery[kit.kit_id]"
                    ></address-input>
                </div>
            </div>
            <div v-if="use_multiple_addresses === false">
                <address-input v-model="formData.main_address"></address-input>
            </div>
        </div>

        <form action="/checkout" method="post" ref="real_form">
            <input type="hidden" name="name" :value="formData.name" />
            <input type="hidden" name="email" :value="formData.email" />
            <input type="hidden" name="phone" :value="formData.phone" />

            <div v-if="use_multiple_addresses === false">
                <div v-for="(address, kit_id) in formData.delivery">
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][line_one]`"
                        :value="formData.main_address.line_one"
                    />
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][line_two]`"
                        :value="formData.main_address.line_two"
                    />
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][city]`"
                        :value="formData.main_address.city"
                    />
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][postal_code]`"
                        :value="formData.main_address.postal_code"
                    />
                </div>
            </div>

            <div v-if="use_multiple_addresses === true">
                <div v-for="(address, kit_id) in formData.delivery">
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][line_one]`"
                        :value="address.line_one"
                    />
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][line_two]`"
                        :value="address.line_two"
                    />
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][city]`"
                        :value="address.city"
                    />
                    <input
                        type="hidden"
                        :name="`delivery[${kit_id}][postal_code]`"
                        :value="address.postal_code"
                    />
                </div>
            </div>
        </form>
        <div class="my-6">
            <button @click="submit" type="button">Checkout</button>
        </div>
        <div>
            <form
                ref="payfast_form"
                action="https://sandbox.payfast.co.za/eng/process"
                method="post"
            >
                <input
                    v-for="(value, key) in payfast"
                    :key="key"
                    type="hidden"
                    :name="key"
                    :value="value"
                />
            </form>
        </div>
    </div>
</template>

<script type="text/babel">
import AddressInput from "./AddressInput";
export default {
    components: {
        AddressInput,
    },

    props: ["basket"],

    data() {
        return {
            use_multiple_addresses: null,
            formData: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
                main_address: {
                    line_one: "",
                    line_two: "",
                    city: "",
                    postal_code: "",
                },
                delivery: {},
            },
            formErrors: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
            },
            payfast: {},
        };
    },

    created() {
        this.formData.delivery = this.basket.kits.reduce((carry, kit) => {
            carry[kit.kit_id] = {
                line_one: "",
                line_two: "",
                city: "",
                postal_code: "",
            };
            return carry;
        }, {});

        if (this.basket.kits.length === 1) {
            this.use_multiple_addresses = false;
        }
    },

    methods: {
        submit() {
            const fd = {
                first_name: this.formData.first_name,
                last_name: this.formData.first_name,
                email: this.formData.email,
                phone: this.formData.phone,
                delivery: {},
            };

            if (this.use_multiple_addresses === true) {
                fd.delivery = this.formData.delivery;
            }

            if (this.use_multiple_addresses === false) {
                fd.delivery[
                    this.basket.kits[0].kit_id
                ] = this.formData.main_address;
            }
            axios
                .post("/checkout", fd)
                .then(({ data }) => (this.payfast = data))
                .then(() =>
                    this.$nextTick().then(() =>
                        this.$refs.payfast_form.submit()
                    )
                );
        },
    },
};
</script>
