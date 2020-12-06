<template>
    <form @submit.prevent="submit" class="max-w-lg">
        <input-field
            class="my-6"
            v-model="formData.code"
            label="The Code"
            help-text="Use uppercase, only letters and numbers, and no spaces"
            :error-msg="formErrors.code"
        ></input-field>

        <div class="my-6">
            <p class="mb-3 form-label">Coupon Dates</p>
            <div class="flex">
                <div
                    :class="{
                        'border-b border-red-500': formErrors.valid_from,
                    }"
                    class="mr-6"
                >
                    <p class="form-label">Valid from:</p>
                    <p
                        v-show="formErrors.valid_from"
                        class="text-sm text-red-600"
                    >
                        {{ formErrors.valid_from }}
                    </p>
                    <date-picker
                        :inline="true"
                        input-class="p-2 bg-gray-100"
                        v-model="formData.valid_from"
                    ></date-picker>
                </div>

                <div
                    :class="{
                        'border-b border-red-500': formErrors.valid_from,
                    }"
                >
                    <p class="form-label">Valid Until:</p>
                    <p
                        v-show="formErrors.valid_until"
                        class="text-sm text-red-600"
                    >
                        {{ formErrors.valid_until }}
                    </p>
                    <date-picker
                        :inline="true"
                        input-class="p-2 bg-gray-100"
                        v-model="formData.valid_until"
                    ></date-picker>
                </div>
            </div>
        </div>

        <div>
            <p class="mb-3 form-label">Discount type</p>
            <radio-input
                class="mb-2"
                label="Lump sum discount"
                :value="1"
                v-model="formData.type"
            ></radio-input>
            <radio-input
                label="Percentage discount"
                :value="2"
                v-model="formData.type"
            ></radio-input>
        </div>

        <div class="my-6">
            <p class="form-label">Discount Value</p>
            <p class="text-gray-500 mb-1 text-sm">
                Enter a number that is either the percentage or the amount in
                Rands off
            </p>
            <input-field
                class="w-42"
                v-model="formData.value"
                type="number"
                :error-msg="formErrors.value"
            ></input-field>
        </div>

        <div class="my-6">
            <p class="form-label">Times it can be used</p>
            <p class="text-gray-500 mb-1 text-sm">
                How many times can this code be redeemed?
            </p>
            <input-field
                class="w-42"
                v-model="formData.uses"
                type="number"
                :error-msg="formErrors.uses"
            ></input-field>
        </div>

        <div class="my-12">
            <submit-button :waiting="waiting" mode="dark"
                >Save Discount Code</submit-button
            >
        </div>
    </form>
</template>

<script type="text/babel">
import InputField from "../Forms/InputField";
import RadioInput from "../Forms/RadioInput";
import DatePicker from "vuejs-datepicker";
import SubmitButton from "../UI/SubmitButton";
import { showError, showSuccess } from "../../../libs/notifications";
import { setValidationErrors } from "../../../libs/forms";
export default {
    components: { RadioInput, InputField, DatePicker, SubmitButton },

    props: ["code"],

    data() {
        return {
            waiting: false,
            formData: {
                code: "",
                valid_from: new Date(),
                valid_until: new Date(),
                type: 1,
                value: 0,
                uses: 0,
            },
            formErrors: {
                code: "",
                valid_from: "",
                valid_until: "",
                type: "",
                value: "",
                uses: "",
            },
        };
    },

    mounted() {
        if (this.code) {
            return (this.formData = {
                code: this.code.code,
                valid_from: new Date(this.code.valid_from),
                valid_until: new Date(this.code.valid_until),
                type: this.code.type,
                value: this.code.value,
                uses: this.code.uses,
            });
        }
    },

    methods: {
        submit() {
            this.waiting = true;
            const action = this.code ? "discounts/update" : "discounts/create";
            const payload = this.code
                ? {
                      code_id: this.code.id,
                      formData: this.formData,
                  }
                : this.formData;

            this.$store
                .dispatch(action, payload)
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        onSuccess() {
            showSuccess("Discount code saved");
            const redirect = this.code
                ? "/discount-codes/${this.code.id}"
                : "/discount-codes";
            this.$router.push(redirect);
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            showError("Failed to save discount code");
        },
    },
};
</script>
