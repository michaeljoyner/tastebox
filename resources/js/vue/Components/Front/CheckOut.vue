<template>
    <div>
        <div
            class="flex flex-col md:flex-row items-center md:items-start justify-around px-6"
        >
            <div>
                <p v-if="profile" class="type-h2 my-12 text-center">
                    Hi {{ profile.first_name }}, this is your Order
                </p>
                <p v-else class="type-h2 my-12 text-center">Your Order</p>
                <div
                    v-for="kit in eligible_kits"
                    :key="kit.id"
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

                <div
                    v-for="kit in ineligible_kits"
                    :key="kit.id"
                    class="mb-4 w-64 text-gray-600"
                >
                    <p class="flex justify-between border-b border-gray-200">
                        <span class="font-bold mr-8">{{ kit.name }}</span>
                        <span class="line-through">R{{ kit.price }}</span>
                    </p>
                    <p class="text-sm">
                        Kit not eligible for order
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
                <p v-show="using_discount" class="w-64 flex justify-between">
                    <span>Discount:</span>
                    <span>R{{ amount_discounted }}</span>
                </p>
                <p class="w-64 flex justify-between">
                    <span>Total:</span>
                    <span>R{{ total_amount }}</span>
                </p>
                <div class="flex justify-end mt-2">
                    <button
                        v-show="!using_discount"
                        @click="showDiscountInput = true"
                        class="text-sm text-green-600 flex items-center leading-none"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            class="fill-current h-4 mr-2"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Use discount code
                    </button>
                    <button
                        v-show="using_discount"
                        @click="clearDiscount"
                        class="text-sm text-gray-600 hover:text-red-500 flex items-center leading-none"
                    >
                        Remove discount
                    </button>
                </div>
            </div>
            <div class="w-full md:w-auto" v-if="!profile">
                <p class="type-h2 my-12 text-center">Your Details</p>

                <div class="w-full max-w-md mx-auto">
                    <div
                        class="my-4 w-full md:w-64"
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
                            class="block border p-2 w-full"
                            id="first_name"
                        />
                    </div>
                    <div
                        class="my-4 md:w-64"
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
                            class="block border p-2 w-full"
                            id="last_name"
                        />
                    </div>

                    <div
                        class="my-4 md:w-64"
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
                            class="block border p-2 w-full"
                            id="email"
                        />
                    </div>
                    <div
                        class="my-4 md:w-64"
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
                            class="block border p-2 w-full"
                            id="phone"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6">
            <p class="type-h2 text-center mt-12 mb-8">Delivery</p>

            <div v-if="use_profile_address">
                <p>
                    We will delivery to your address at
                    {{ profile.full_address }}
                </p>
                <div class="flex justify-end py-3">
                    <button
                        @click="use_profile_address = false"
                        class="text-gray-500 hover:text-green-600 type-b3"
                    >
                        Use a different address
                    </button>
                </div>
            </div>
            <div v-else>
                <p class="max-w-lg mx-auto text-center mb-8">
                    Note: We currently ONLY deliver in Pietermaritzburg and
                    surrounding areas, including Nottingham Road, Kloof and
                    Pinetown. If you are unsure if you will receive your
                    delivery, please contact us before you place your order.
                </p>

                <div
                    v-if="has_multiple_kits"
                    class="flex flex-col md:flex-row justify-around items-center my-8 md:my-12"
                >
                    <p class="mb-4 max-w-sm">
                        You have ordered more than one box. Would you like to
                        have any of the boxes sent to different address?
                    </p>
                    <div>
                        <label class="block mb-4">
                            <input
                                type="radio"
                                :value="false"
                                class="text-green-500 mr-2 focus:outline-none"
                                v-model="use_multiple_addresses"
                            />
                            <span
                                ><strong>No</strong>, use the same address for
                                all boxes.</span
                            >
                        </label>

                        <label class="block mb-4">
                            <input
                                type="radio"
                                :value="true"
                                class="text-green-500 mr-2 focus:outline-none"
                                v-model="use_multiple_addresses"
                            />
                            <span
                                ><strong>Yes</strong>, I need to send to
                                different addresses.</span
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
                            :error-msg="isInvalidAddress(kit.id)"
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
                        :error-msg="invalidAddresses.length"
                    ></address-input>
                </div>
            </div>
        </div>

        <div class="my-12" v-if="!profile">
            <p class="text-center type-h3 mb-3">Keep up to date.</p>
            <div>
                <div>
                    <label
                        for="newsletter_signup"
                        class="max-w-md px-6 mx-auto block text-center"
                    >
                        <input
                            type="checkbox"
                            id="newsletter_signup"
                            v-model="formData.subscribe_to_newsletter"
                        />
                        <span class="type-b3"
                            >I agree that TasteBox can send me emails.</span
                        >
                    </label>
                </div>
                <div v-if="false">
                    <label
                        for="sms_reminder_signup"
                        class="max-w-md px-6 mx-auto block text-center"
                    >
                        <input
                            type="checkbox"
                            id="sms_reminder_signup"
                            v-model="formData.get_sms_reminder"
                        />
                        <span class="type-b3"
                            >TasteBox can send me an order reminder by SMS every
                            Thursday.</span
                        >
                    </label>
                </div>

                <p
                    class="type-b3 text-center text-gray-600 mt-2 max-w-md mx-auto"
                >
                    TasteBox will only send emails or SMSs relevant to our meals
                    and offerings, and will never sell your information.
                </p>
            </div>
        </div>

        <div class="my-12 text-center">
            <submit-button @click="submit" role="button" :waiting="waiting">
                <span
                    class="flex items-center leading-none"
                    :class="{ 'opacity-0': waiting }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        class="fill-current h-4 mr-3"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>Pay Now</span>
                </span>
            </submit-button>
        </div>
        <div>
            <form ref="payfast_form" :action="payfastUrl" method="post">
                <input
                    v-for="(value, key) in payfast"
                    :key="key"
                    type="hidden"
                    :name="key"
                    :value="value"
                />
            </form>
        </div>
        <modal :show="showDiscountInput" @close="showDiscountInput = false">
            <div class="w-full mx-auto max-w-md py-6 px-3 bg-white rounded-lg">
                <div>
                    <label
                        class="text-sm font-bold text-gray-700"
                        for="discount_code"
                        >Enter your Discount Code</label
                    >
                    <p
                        v-show="discount_status_error"
                        class="text-sm text-red-600"
                    >
                        {{ discount_status_error }}
                    </p>
                    <input
                        class="block p-2 border w-full"
                        type="text"
                        v-model="check_discount_code"
                    />
                </div>
                <div class="flex justify-end mt-6">
                    <button
                        type="button"
                        @click="showDiscountInput = false"
                        class="mr-4"
                    >
                        Cancel
                    </button>
                    <submit-button
                        @click="checkDiscountCode"
                        role="button"
                        :waiting="checking_code"
                    >
                        Apply Code
                    </submit-button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">
import AddressInput from "./AddressInput";
import SubmitButton from "./SubmitButton";
import Modal from "../Modal";
import {
    clearValidationErrors,
    setValidationErrors,
} from "../../../libs/forms";
export default {
    components: {
        AddressInput,
        SubmitButton,
        Modal,
    },

    props: ["basket", "payfast-url", "profile"],

    data() {
        return {
            use_profile_address: !!this.profile,
            use_multiple_addresses: false,
            showDiscountInput: false,
            check_discount_code: "",
            checking_code: false,
            discount_status_error: "",
            discount_value: 0,
            discount_type: "",
            waiting: false,
            formData: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
                discount_code: "",
                main_address: {
                    line_one: "",
                    line_two: "",
                    city: "",
                    postal_code: "",
                },
                delivery: {},
                subscribe_to_newsletter: false,
                get_sms_reminder: false,
            },
            formErrors: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
            },
            invalidAddresses: [],
            payfast: {},
        };
    },

    computed: {
        has_multiple_kits() {
            return this.eligible_kits.length > 1;
        },

        eligible_kits() {
            return this.basket.kits.filter((kit) => kit.eligible_for_order);
        },

        ineligible_kits() {
            return this.basket.kits.filter((kit) => !kit.eligible_for_order);
        },

        hasInvalidAddress() {
            return this.invalidAddresses.length > 0;
        },

        using_discount() {
            return this.formData.discount_code && this.discount_value > 0;
        },

        amount_discounted() {
            const price = this.basket.total_price;
            if (this.discount_type === "lump") {
                return this.discount_value;
            }

            if (this.discount_type === "percent") {
                const percent = this.discount_value / 100;
                return parseInt(price * percent);
            }

            return 0;
        },

        total_amount() {
            return this.basket.total_price - this.amount_discounted;
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

    mounted() {
        try {
            fbq("track", "InitiateCheckout", {
                currency: "ZAR",
                value: this.total_amount,
            });
        } catch (e) {}
    },

    methods: {
        submit() {
            this.waiting = true;
            this.formErrors = clearValidationErrors(this.formErrors);
            this.clearInvalidAddresses();

            const fd = {
                first_name: this.formData.first_name,
                last_name: this.formData.last_name,
                email: this.formData.email,
                phone: this.formData.phone,
                discount_code: this.formData.discount_code,
                subscribe_to_newsletter: this.formData.subscribe_to_newsletter,
                get_sms_reminder: this.formData.get_sms_reminder,
                delivery: this.getDeliveryDetails(),
            };

            axios
                .post("/checkout", fd)
                .then(({ data }) => this.onSuccess(data))
                .catch(({ response }) => this.onError(response));
        },

        getDeliveryDetails() {
            if (this.use_profile_address) {
                return null;
            }

            if (this.use_multiple_addresses === true) {
                return this.formData.delivery;
            }

            if (this.use_multiple_addresses === false) {
                const del = {};

                del[this.basket.kits[0].id] = this.formData.main_address;
                return del;
            }
        },

        onSuccess(payfast_data) {
            this.payfast = payfast_data;

            this.$nextTick().then(() => {
                this.$refs.payfast_form.submit();
            });
        },

        onError({ status, data }) {
            this.waiting = false;
            if (status === 422) {
                this.setInvalidAddresses(data.errors);
                return (this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
        },

        setInvalidAddresses(errors) {
            const invalid = new Set(
                Object.keys(errors)
                    .filter((key) => key.indexOf("delivery.") === 0)
                    .map((key) => key.slice(9, 45))
            );
            this.invalidAddresses = [...invalid];
        },

        clearInvalidAddresses() {
            this.invalidAddresses = [];
        },

        isInvalidAddress(kit_id) {
            return this.invalidAddresses.includes(kit_id);
        },

        checkDiscountCode() {
            this.checking_code = true;
            this.discount_status_error = "";
            axios
                .post("/discount-code-status", {
                    discount_code: this.check_discount_code,
                })
                .then(({ data }) =>
                    data.is_valid
                        ? this.applyCode(data)
                        : this.handleInvalidCode(data)
                )
                .catch(this.onDiscountStatusError)
                .then(() => (this.checking_code = false));
        },

        applyCode(code) {
            this.formData.discount_code = code.code;
            this.discount_value = code.value;
            this.discount_type = code.type;
            this.check_discount_code = "";
            this.showDiscountInput = false;
        },

        handleInvalidCode(code) {
            this.discount_status_error = code.message;
        },

        onDiscountStatusError() {
            this.discount_status_error =
                "Sorry, we were unable to check on that code for you.";
        },

        clearDiscount() {
            this.formData.discount_code = "";
            this.check_discount_code = "";
            this.discount_value = 0;
            this.discount_type = "";
        },
    },
};
</script>
