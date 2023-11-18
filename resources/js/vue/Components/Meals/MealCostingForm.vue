<template>
    <div>
        <form @submit.prevent="save">
            <InputField
                class="my-6 w-64"
                label="Cost"
                v-model="form.data.cost"
                :error-msg="form.errors.cost"
            />

            <div class="my-6">
                <p class="form-label">Price Tier:</p>
                <div class="flex flex-wrap mt-2">
                    <div
                        v-for="tier in tiers"
                        class="mr-6 flex space-x-2 items-center px-2 py-1 border border-black rounded-md"
                    >
                        <input
                            type="radio"
                            v-model="form.data.tier"
                            :value="tier.value"
                            :id="`tier_${tier.value}`"
                        />
                        <label :for="`tier_${tier.value}`">{{
                            tier.description
                        }}</label>
                    </div>
                </div>
            </div>

            <DatePicker
                v-model="form.data.date_costed"
                :error-msg="form.errors.date_costed"
                class="my-6"
                label="Date Costed"
            ></DatePicker>

            <TextAreaField
                v-model="form.data.note"
                :error-msg="form.errors.note"
                class="my-6"
                height="small"
                label="Note"
            />

            <div>
                <SubmitButton :waiting="saving">Save Costing</SubmitButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import { httpAction } from "../../../libs/httpAction";
import { useStore } from "vuex";
import { useForm } from "../../../libs/useForm";
import { toStandardDateFormat } from "../../../libs/dates";
import { showError, showSuccess } from "../../../libs/notifications";
import InputField from "../Forms/InputField.vue";
import { computed } from "vue";
import DatePicker from "../Forms/DatePicker.vue";
import TextAreaField from "../Forms/TextAreaField.vue";
import SubmitButton from "../UI/SubmitButton.vue";

const props = defineProps({ costing: Object, mealId: Number });
const emit = defineEmits(["saved"]);
const store = useStore();

const tiers = computed(() => store.state.meals.tiers);

const { form, clearFormErrors, setFormErrors, handleFormError } = useForm({
    cost: props.costing?.cost || "",
    tier: props.costing?.tier_value || 2,
    note: props.costing?.note || "",
    date_costed: props.costing?.date_costed || toStandardDateFormat(new Date()),
});

const [saving, save] = httpAction(
    () => {
        clearFormErrors();
        const action = props.costing
            ? "meals/updateCosting"
            : "meals/addCosting";
        const payload = props.costing
            ? { costing_id: props.costing.id, formData: form.data }
            : { meal_id: props.mealId, formData: form.data };

        return store.dispatch(action, payload);
    },
    () => {
        showSuccess("Costing saved");
        emit("saved");
    },
    (resp) => {
        handleFormError(resp, "Failed to save costing");
    }
);
</script>
