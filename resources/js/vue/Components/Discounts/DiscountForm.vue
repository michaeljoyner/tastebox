<template>
    <form @submit.prevent="save" class="max-w-lg">
        <div class="my-6" v-show="!code">
            <p class="form-label">Who is this discount for?</p>
            <p class="type-b3 text-gray-500 my-1">
                Choose whether this discount is for members or the general
                public.
            </p>
            <div class="my-4 flex space-x-6">
                <radio-input
                    class="mb-2"
                    label="Everyone"
                    value="public"
                    v-model="form.data.award_to"
                ></radio-input>

                <radio-input
                    class="mb-2"
                    label="Members"
                    value="members"
                    v-model="form.data.award_to"
                ></radio-input>
            </div>
        </div>

        <input-field
            class="my-6"
            v-model="form.data.code"
            label="The Code"
            help-text="Use uppercase, only letters and numbers, and no spaces"
            :error-msg="form.errors.code"
        ></input-field>

        <div class="my-6">
            <p class="mb-3 form-label">Coupon Dates</p>
            <div class="flex">
                <div
                    :class="{
                        'border-b border-red-500': form.errors.valid_from,
                    }"
                    class="mr-6"
                >
                    <p class="form-label">Valid from:</p>
                    <p
                        v-show="form.errors.valid_from"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.valid_from }}
                    </p>
                    <input type="date" v-model="form.data.valid_from" />
                </div>

                <div
                    :class="{
                        'border-b border-red-500': form.errors.valid_from,
                    }"
                >
                    <p class="form-label">Valid Until:</p>
                    <p
                        v-show="form.errors.valid_until"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.valid_until }}
                    </p>
                    <input type="date" v-model="form.data.valid_until" />
                </div>
            </div>
        </div>

        <div>
            <p class="mb-3 form-label">Discount type</p>
            <radio-input
                class="mb-2"
                label="Lump sum discount"
                :value="1"
                v-model="form.data.type"
            ></radio-input>
            <radio-input
                label="Percentage discount"
                :value="2"
                v-model="form.data.type"
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
                v-model="form.data.value"
                type="number"
                :error-msg="form.errors.value"
            ></input-field>
        </div>

        <div class="my-6">
            <p class="form-label">Times it can be used</p>
            <p class="text-gray-500 mb-1 text-sm">
                How many times can this code be redeemed?
            </p>
            <input-field
                class="w-42"
                v-model="form.data.uses"
                type="number"
                :error-msg="form.errors.uses"
            ></input-field>
        </div>

        <div class="my-12">
            <submit-button :waiting="saving" mode="dark"
                >Save Discount Code</submit-button
            >
        </div>
    </form>
</template>

<script type="text/babel">
import InputField from "../Forms/InputField";
import RadioInput from "../Forms/RadioInput";
import SubmitButton from "../UI/SubmitButton";
import DatePicker from "../Forms/DatePicker";
import { showError, showSuccess } from "../../../libs/notifications";
import { setValidationErrors } from "../../../libs/forms";
import { useStore } from "vuex";
import { useForm } from "../../../libs/useForm";
import { computed, watchEffect } from "vue";
import { httpAction } from "../../../libs/httpAction";
import { useRouter } from "vue-router";
export default {
    components: { RadioInput, InputField, DatePicker, SubmitButton },

    props: ["code"],

    setup(props, { emit }) {
        const store = useStore();
        const router = useRouter();

        const { form, setFormErrors, clearFormErrors } = useForm({
            code: "",
            valid_from: new Date().toLocaleDateString("en-CA"),
            valid_until: new Date().toLocaleDateString("en-CA"),
            type: 1,
            value: 0,
            uses: 0,
            award_to: "public",
        });

        watchEffect(() => {
            if (props.code) {
                form.data = {
                    code: props.code.code,
                    valid_from: new Date(
                        props.code.valid_from
                    ).toLocaleDateString("en-CA"),
                    valid_until: new Date(
                        props.code.valid_until
                    ).toLocaleDateString("en-CA"),
                    type: props.code.type,
                    value: props.code.value,
                    uses: props.code.uses,
                };
            }
        });

        const forMembers = computed(() =>
            props.code
                ? props.code.is_member_discount
                : form.data.award_to === "members"
        );

        const getAction = () => {
            if (forMembers.value) {
                return props.code
                    ? "members/updateGeneralDiscount"
                    : "members/createGeneralDiscount";
            }

            return props.code ? "discounts/update" : "discounts/create";
        };

        const getPayload = () => {
            if (forMembers.value) {
                return props.code
                    ? {
                          discount_tag: props.code.discount_tag,
                          formData: form.data,
                      }
                    : form.data;
            }

            return props.code
                ? {
                      code_id: props.code.id,
                      formData: form.data,
                  }
                : form.data;
        };

        const [saving, save] = httpAction(
            () => store.dispatch(getAction(), getPayload()),
            () => {
                showSuccess("Discount saved");
                router.push("/discount-codes");
            },
            (resp) => {
                console.log(resp);
                showError("Failed to save discount");
            }
        );

        return { form, save, saving };
    },
};
</script>
