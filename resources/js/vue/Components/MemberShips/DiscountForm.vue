<template>
    <form @submit.prevent="save">
        <div class="my-6">
            <input-field
                label="Discount code"
                v-model="form.data.code"
            ></input-field>
        </div>

        <div class="my-6">
            <label>
                <span class="form-label">Valid from:</span>
                <input
                    type="date"
                    class="w-full block mt-1"
                    v-model="form.data.valid_from"
                />
            </label>
        </div>

        <div class="my-6">
            <label>
                <span class="form-label">Valid Until:</span>
                <input
                    type="date"
                    class="w-full block mt-1"
                    v-model="form.data.valid_until"
                />
            </label>
        </div>

        <div>
            <p class="form-label">Discount type:</p>
            <div class="flex space-x-6 items-center">
                <label>
                    <input
                        type="radio"
                        :value="1"
                        v-model="form.data.type"
                        class="text-pink-500 focus:ring-0 mr-1"
                    />
                    <span>Lump Sum</span>
                </label>

                <label>
                    <input
                        type="radio"
                        :value="2"
                        v-model="form.data.type"
                        class="text-pink-500 focus:ring-0 mr-1"
                    />
                    <span>Percentage</span>
                </label>
            </div>
        </div>

        <div class="my-6">
            <input-field
                type="number"
                label="Discount value"
                v-model="form.data.value"
                :error-msg="form.errors.value"
            ></input-field>
        </div>

        <div class="my-6">
            <submit-button :waiting="saving">Save Discount</submit-button>
        </div>
    </form>
</template>

<script type="text/babel">
import { useForm } from "../../../libs/useForm";
import InputField from "../Forms/InputField";
import { toStandardDateFormat } from "../../../libs/dates";
import SubmitButton from "../UI/SubmitButton";
import { useStore } from "vuex";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import { watch, watchEffect } from "vue";

export default {
    components: { SubmitButton, InputField },
    props: ["discount"],

    setup(props, { emit }) {
        const store = useStore();

        const { form, setFormErrors, clearFormErrors } = useForm({
            code: "",
            valid_from: toStandardDateFormat(props.discount?.valid_from),
            valid_until: toStandardDateFormat(props.discount?.valid_until),
            type: "lump",
            value: 0,
        });

        watch(
            () => props.discount,
            (discount) => {
                if (discount) {
                    return (form.data = {
                        code: discount.code,
                        valid_from: discount.valid_from,
                        valid_until: discount.valid_until,
                        type: discount.type,
                        value: discount.value,
                    });
                }

                form.data = {
                    code: "",
                    valid_from: "",
                    valid_until: "",
                    type: 1,
                    value: 0,
                };
            }
        );

        const [saving, save] = httpAction(
            () => {
                const action = props.discount
                    ? "members/updateDiscount"
                    : "members/createDiscount";
                const payload = props.discount
                    ? { discount_id: props.discount.id, formData: form.data }
                    : form.data;
                return store.dispatch(action, payload);
            },
            () => {
                showSuccess("Discount saved");
                emit("saved");
            },
            () => showError("Failed to save discount")
        );

        return { save, saving, form };
    },
};
</script>
