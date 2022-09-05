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
                        class="text-sm text-green-600 flex items-center leading-none focus:ring-0 focus:outline-none"
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
                        class="my-4 w-full md:w-80"
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
                        class="my-4 md:w-80"
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
                        class="my-4 md:w-80"
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
                        class="my-4 md:w-80"
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
                            class="mr-2"
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
                <div
                    class="flex justify-between border-b border-gray-200 pb-2 mb-4"
                >
                    <p class="type-h4">Add a Discount</p>
                    <button
                        type="button"
                        @click="showDiscountInput = false"
                        class="mr-4 text-gray-500 hover:text-green-600 grid place-items-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 fill-current"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
                <div v-if="profile && discounts.length" class="mt-6 mb-8">
                    <p class="font-semibold text-gray-500 text-sm mb-4">
                        Available discounts for you:
                    </p>
                    <div class="border-y border-gray-200 divide-y">
                        <div
                            v-for="discount in discounts"
                            :key="discount.id"
                            class="flex justify-between py-3"
                        >
                            <p class="font-bold">
                                {{ discount.value_string }} off
                            </p>
                            <button
                                @click="applyMemberDiscount(discount)"
                                class="text-xs bg-green-100 hover:bg-green-200 border border-green-700 text-green-700 px-2 py-1 rounded-full"
                            >
                                Use
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="font-semibold text-gray-500 text-sm mb-4">
                        Enter a discount code:
                    </p>

                    <div
                        class="flex border border-green-500 rounded-full focus-within:ring-green-500 focus-within:ring-1 overflow-hidden"
                    >
                        <input
                            class="block rounded-md border-none py-2 pl-4 flex-1 focus:ring-0"
                            type="text"
                            id="discount_code"
                            placeholder="Discount code"
                            v-model="check_discount_code"
                        />
                        <submit-button
                            @click="checkDiscountCode"
                            role="button"
                            :waiting="checking_code"
                        >
                            Use
                        </submit-button>
                    </div>
                    <p
                        v-show="discount_status_error"
                        class="text-sm text-red-600 text-center"
                    >
                        {{ discount_status_error }}
                    </p>
                </div>

                <div class="flex justify-end mt-6"></div>
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

    props: ["basket", "payfast-url", "profile", "discounts"],

    data() {
        return {
            use_profile_address: this.profile && this.profile.is_complete,
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
                subscribe_to_newsletter: false,
                get_sms_reminder: false,
                member_discount_id: null,
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
        eligible_kits() {
            return this.basket.kits.filter((kit) => kit.eligible_for_order);
        },

        ineligible_kits() {
            return this.basket.kits.filter((kit) => !kit.eligible_for_order);
        },

        using_discount() {
            return (
                (this.formData.discount_code ||
                    this.formData.member_discount_id) &&
                this.discount_value > 0
            );
        },

        amount_discounted() {
            const price = this.basket.total_price;
            if (this.discount_type === 1) {
                return this.discount_value;
            }

            if (this.discount_type === 2) {
                const percent = this.discount_value / 100;
                return parseInt(price * percent);
            }

            return 0;
        },

        total_amount() {
            return this.basket.total_price - this.amount_discounted;
        },
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

            const fd = {
                first_name: this.formData.first_name,
                last_name: this.formData.last_name,
                email: this.formData.email,
                phone: this.formData.phone,
                discount_code: this.formData.discount_code,
                member_discount_id: this.formData.member_discount_id,
                subscribe_to_newsletter: this.formData.subscribe_to_newsletter,
                get_sms_reminder: this.formData.get_sms_reminder,
            };

            axios
                .post("/checkout", fd)
                .then(({ data }) => this.onSuccess(data))
                .catch(({ response }) => this.onError(response));
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
                return (this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
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
            this.dispatchSuccessEvent("Discount applied!");
        },

        applyMemberDiscount(code) {
            this.formData.member_discount_id = code.id;
            this.discount_value = code.value;
            this.discount_type = code.type;
            this.check_discount_code = "";
            this.showDiscountInput = false;
            this.dispatchSuccessEvent("Discount applied!");
        },

        dispatchSuccessEvent(text) {
            const ev = new CustomEvent("toasties:success", {
                detail: text,
            });
            window.dispatchEvent(ev);
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
            this.formData.member_discount_id = null;
            this.check_discount_code = "";
            this.discount_value = 0;
            this.discount_type = "";
            this.dispatchSuccessEvent("Discount removed");
        },
    },
};
</script>
