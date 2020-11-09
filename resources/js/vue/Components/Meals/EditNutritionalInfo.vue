<template>
    <div>
        <sub-header title="Edit Nutritional Info">
            <router-link
                class="btn"
                :to="`/meals/${meal.id}/manage/nutritional-info`"
                >Back</router-link
            >
        </sub-header>

        <div class="my-12">
            <p class="my-6">
                <strong>Note: </strong>All values should be numerical or left
                blank
            </p>
            <form @submit.prevent="submit" class="max-w-sm">
                <input-field
                    class="my-6"
                    label="Energy (cals)"
                    v-model="formData.serving_energy"
                    :error-msg="formErrors.serving_energy"
                ></input-field>

                <input-field
                    class="my-6"
                    label="Carbs (g)"
                    v-model="formData.serving_carbs"
                    :error-msg="formErrors.serving_carbs"
                ></input-field>

                <input-field
                    class="my-6"
                    label="Fat (g)"
                    v-model="formData.serving_fat"
                    :error-msg="formErrors.serving_fat"
                ></input-field>

                <input-field
                    class="my-6"
                    label="Protein (g)"
                    v-model="formData.serving_protein"
                    :error-msg="formErrors.serving_protein"
                ></input-field>

                <div class="my-6">
                    <submit-button :waiting="waiting" mode="dark"
                        >Save Nutritional Info</submit-button
                    >
                </div>
            </form>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader";
import InputField from "../Forms/InputField";
import SubmitButton from "../UI/SubmitButton";
import { showError, showSuccess } from "../../../libs/notifications";
import {
    clearValidationErrors,
    setValidationErrors,
} from "../../../libs/forms";
export default {
    components: { SubHeader, InputField, SubmitButton },

    props: ["meal"],

    data() {
        return {
            waiting: false,
            formData: {
                serving_energy: this.meal.serving_energy,
                serving_carbs: this.meal.serving_carbs,
                serving_fat: this.meal.serving_fat,
                serving_protein: this.meal.serving_protein,
            },
            formErrors: {
                serving_energy: "",
                serving_carbs: "",
                serving_fat: "",
                serving_protein: "",
            },
        };
    },

    methods: {
        submit() {
            this.waiting = true;
            this.formErrors = clearValidationErrors(this.formErrors);

            this.$store
                .dispatch("meals/updateNutritionalInfo", {
                    meal_id: this.meal.id,
                    formData: this.formData,
                })
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        onSuccess() {
            showSuccess("Nutritional Info saved");
            this.$router.push(`/meals/${this.meal.id}/manage/nutritional-info`);
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            showError("Failed to save nutritional info");
        },
    },
};
</script>
