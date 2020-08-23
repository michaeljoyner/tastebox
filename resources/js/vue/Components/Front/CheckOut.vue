<template>
    <div>
        <div
            class="flex flex-col md:flex-row items-center md:items-start justify-around px-6"
        >
            <div>
                <p class="text-xl my-12 text-center font-bold">Your Order</p>
                <div v-for="kit in basket.kits" :key="kit.id" class="mb-4 w-64">
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
                    <span>R0</span>
                </p>
                <p class="w-64 flex justify-between">
                    <span>Total:</span>
                    <span>R{{ basket.total_price }}</span>
                </p>
            </div>
            <div class="w-full md:w-auto">
                <p class="text-xl my-12 text-center font-bold">Your Details</p>

                <div class="w-full max-w-md mx-auto">
                    <div
                        class="my-4 w-full"
                        :class="{
                            'border-b border-red-400': formErrors.first_name,
                        }"
                    >
                        <label
                            class="text-sm font-bold text-gray-700"
                            for="first_name"
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
                            class="block border p-2 w-full md:w-64"
                            id="first_name"
                        />
                    </div>
                    <div
                        class="my-4"
                        :class="{
                            'border-b border-red-400': formErrors.last_name,
                        }"
                    >
                        <label
                            class="text-sm font-bold text-gray-700"
                            for="last_name"
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
                            class="block border p-2 w-full md:w-64"
                            id="last_name"
                        />
                    </div>

                    <div
                        class="my-4"
                        :class="{ 'border-b border-red-400': formErrors.email }"
                    >
                        <label
                            class="text-sm font-bold text-gray-700"
                            for="email"
                            >Your Email</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.email"
                            >{{ formErrors.email }}</span
                        >
                        <input
                            type="email"
                            name="email"
                            v-model="formData.email"
                            class="block border p-2 w-full md:w-64"
                            id="email"
                        />
                    </div>
                    <div
                        class="my-4"
                        :class="{ 'border-b border-red-400': formErrors.phone }"
                    >
                        <label
                            class="text-sm font-bold text-gray-700"
                            for="phone"
                            >Cell Number</label
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
                            class="block border p-2 w-full md:w-64"
                            id="phone"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6">
            <p class="text-xl font-bold text-center my-12">Delivery</p>

            <div
                v-if="has_multiple_kits"
                class="flex flex-col md:flex-row justify-around items-center my-8 md:my-12"
            >
                <p class="mb-4 max-w-sm">
                    You have ordered more than one box. Would you like to have
                    any of the boxes sent to different address?
                </p>
                <div>
                    <label class="block mb-4">
                        <input
                            type="radio"
                            :value="false"
                            v-model="use_multiple_addresses"
                        />
                        <span
                            ><strong>No</strong>, use the same address for all
                            boxes.</span
                        >
                    </label>

                    <label class="block mb-4">
                        <input
                            type="radio"
                            :value="true"
                            v-model="use_multiple_addresses"
                        />
                        <span
                            ><strong>Yes</strong>, I need to send to different
                            addresses.</span
                        >
                    </label>
                </div>
            </div>

            <div v-if="use_multiple_addresses === true">
                <div
                    v-for="kit in basket.kits"
                    :key="kit.id"
                    class="flex flex-col md:flex-row justify-around p-6 border md:border-0"
                >
                    <div class="mb-4 md:w-64">
                        <p class="font-bold">{{ kit.name }}</p>
                        <p>
                            Delivery from:
                            <span class="font-bold text-gray-700">{{
                                kit.delivery_date
                            }}</span>
                        </p>
                        <p
                            v-for="meal in kit.meals"
                            :key="meal.id"
                            class="text-sm"
                        >
                            {{ meal.name }} (for {{ meal.servings }})
                        </p>
                    </div>
                    <address-input
                        class="max-w-md"
                        v-model="formData.delivery[kit.id]"
                    ></address-input>
                </div>
            </div>
            <div
                v-if="use_multiple_addresses === false"
                class="flex flex-col items-center md:items-start md:flex-row justify-around"
            >
                <div v-if="has_multiple_kits" class="md:w-64">
                    <p class="mb-4">
                        You have ordered
                        <span class="font-bold"
                            >{{ basket.kits.length }} meal kits</span
                        >. They will all be delivered to this address.
                    </p>
                </div>
                <div v-else class="hidden md:block md:w-64">
                    <p class="font-bold">{{ basket.kits[0].name }}</p>
                    <p>
                        Delivery from:
                        <span class="font-bold text-gray-700">{{
                            basket.kits[0].delivery_date
                        }}</span>
                    </p>
                    <p
                        v-for="meal in basket.kits[0].meals"
                        :key="meal.id"
                        class="text-sm"
                    >
                        {{ meal.name }} (for {{ meal.servings }})
                    </p>
                </div>
                <address-input
                    class="max-w-md"
                    v-model="formData.main_address"
                ></address-input>
            </div>
        </div>

        <div class="my-6 text-center">
            <button
                @click="submit"
                type="button"
                class="px-4 py-2 rounded bg-green-600 hover:bg-green-500 text-white font-bold"
            >
                Pay Now
            </button>
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
            use_multiple_addresses: false,
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

    computed: {
        has_multiple_kits() {
            return this.basket.kits.length > 1;
        },
    },

    created() {
        this.formData.delivery = this.basket.kits.reduce((carry, kit) => {
            carry[kit.id] = {
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
                last_name: this.formData.last_name,
                email: this.formData.email,
                phone: this.formData.phone,
                delivery: {},
            };

            if (this.use_multiple_addresses === true) {
                fd.delivery = this.formData.delivery;
            }

            if (this.use_multiple_addresses === false) {
                fd.delivery[
                    this.basket.kits[0].id
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
