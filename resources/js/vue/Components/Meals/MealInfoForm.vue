<template>
    <form @submit.prevent="submit" v-if="classifications" class="max-w-lg">
        <input-field
            class="my-6"
            label="Name"
            v-model="formData.name"
            :error-msg="formErrors.name"
        ></input-field>

        <div class="my-6">
            <p class="form-label">Price Tier:</p>
            <div class="flex flex-wrap mt-2">
                <div
                    v-for="tier in tiers"
                    class="mr-6 flex space-x-2 items-center px-2 py-1 border border-black rounded-md"
                >
                    <input
                        type="radio"
                        v-model="formData.price_tier"
                        :value="tier.value"
                        :id="`tier_${tier.value}`"
                    />
                    <label :for="`tier_${tier.value}`">{{
                        tier.description
                    }}</label>
                </div>
            </div>
        </div>

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

        <text-area-field
            class="my-6"
            label="Meal Card Description"
            v-model="formData.meal_card_description"
            :error-msg="formErrors.meal_card_description"
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
} from "../../../libs/forms.js";
import {
    showError,
    showSuccess,
    showWarning,
} from "../../../libs/notifications.js";
import InputField from "../Forms/InputField.vue";
import TextAreaField from "../Forms/TextAreaField.vue";
import SubmitButton from "../UI/SubmitButton.vue";

export default {
    components: { SubmitButton, TextAreaField, InputField },
    props: ["meal"],

    data() {
        return {
            waiting: false,
            formData: {
                name: "",
                description: "",
                meal_card_description: "",
                prep_time: null,
                cook_time: null,
                allergens: "",
                classifications: [],
                price_tier: 2,
            },
            formErrors: {
                name: "",
                description: "",
                meal_card_description: "",
                prep_time: "",
                cook_time: "",
                allergens: "",
                classifications: "",
                price_tier: "",
            },
        };
    },

    computed: {
        classifications() {
            return this.$store.state.meals.classifications;
        },

        tiers() {
            return this.$store.state.meals.tiers;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchClassifications");

        if (this.meal) {
            this.formData = {
                name: this.meal.name,
                description: this.meal.description,
                meal_card_description: this.meal.meal_card_description,
                prep_time: this.meal.prep_time,
                cook_time: this.meal.cook_time,
                allergens: this.meal.allergens,
                classifications: this.meal.classifications.map((c) => c.id),
                price_tier: this.meal.tier_value || 2,
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
