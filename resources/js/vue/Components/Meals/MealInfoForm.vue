<template>
    <form @submit.prevent="submit" v-if="classifications" class="max-w-lg">
        <input-field
            class="my-6"
            label="Name"
            v-model="formData.name"
            :error-msg="formErrors.name"
        ></input-field>

        <div class="my-6">
            <p class="form-label">Classifications:</p>
            <div class="flex flex-wrap mt-2">
                <div v-for="classification in classifications" class="mr-6">
                    <input
                        type="checkbox"
                        v-model="formData.classifications"
                        :value="classification.id"
                        :id="`classification_${classification.id}`"
                    />
                    <label :for="`classification_${classification.id}`">{{
                        classification.name
                    }}</label>
                </div>
            </div>
        </div>

        <text-area-field
            class="my-6"
            label="Description"
            v-model="formData.description"
            :error-msg="formErrors.description"
        ></text-area-field>

        <input-field
            class="my-6"
            label="Allergens"
            v-model="formData.allergens"
            :error-msg="formErrors.allergens"
        ></input-field>

        <div class="my-6 flex justify-between">
            <input-field
                class="w-48"
                label="Prep time (mins)"
                v-model="formData.prep_time"
                :error-msg="formErrors.prep_time"
            ></input-field>

            <input-field
                class="w-48"
                label="Cook time (mins)"
                v-model="formData.cook_time"
                :error-msg="formErrors.cook_time"
            ></input-field>
        </div>

        <div class="my-6">
            <submit-button :waiting="waiting" mode="dark"
                >Save Meal</submit-button
            >
        </div>
    </form>
</template>

<script type="text/babel">
import {
    clearValidationErrors,
    setValidationErrors,
} from "../../../libs/forms";
import {
    showError,
    showSuccess,
    showWarning,
} from "../../../libs/notifications";
import InputField from "../Forms/InputField";
import TextAreaField from "../Forms/TextAreaField";
import SubmitButton from "../UI/SubmitButton";

export default {
    components: { SubmitButton, TextAreaField, InputField },
    props: ["meal"],

    data() {
        return {
            waiting: false,
            formData: {
                name: "",
                description: "",
                prep_time: null,
                cook_time: null,
                allergens: "",
                classifications: [],
            },
            formErrors: {
                name: "",
                description: "",
                prep_time: "",
                cook_time: "",
                allergens: "",
                classifications: "",
            },
        };
    },

    computed: {
        classifications() {
            return this.$store.state.meals.classifications;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchClassifications");

        if (this.meal) {
            this.formData = {
                name: this.meal.name,
                description: this.meal.description,
                prep_time: this.meal.prep_time,
                cook_time: this.meal.cook_time,
                allergens: this.meal.allergens,
                classifications: this.meal.classifications.map((c) => c.id),
            };
        }
    },

    methods: {
        submit() {
            this.waiting = true;
            this.formErrors = clearValidationErrors(this.formErrors);

            const action = this.meal ? "meals/updateInfo" : "meals/createMeal";
            const payload = this.meal
                ? { meal_id: this.meal.id, formData: this.formData }
                : this.formData;

            this.$store
                .dispatch(action, payload)
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        onSuccess(meal) {
            showSuccess("Meal info saved");
            const redirect = this.meal
                ? `/meals/${this.meal.id}/manage/info`
                : `/meals/${meal.id}/manage/info`;
            this.$router.push(redirect);
        },

        onError({ status, data }) {
            if (status === 422) {
                this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                );
                return showWarning("Some input is not valid");
            }
            showError("Failed to save meal info");
        },
    },
};
</script>
