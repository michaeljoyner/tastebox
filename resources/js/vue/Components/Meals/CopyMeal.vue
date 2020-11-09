<template>
    <div class="p-6 shadow">
        <p class="font-bold">Copy Meal</p>
        <div class="">
            <form @submit.prevent="submit">
                <p class="my-4 text-sm">
                    You are about to make a copy of {{ meal.name }}. Do not
                    forget to update the necessary parts or you will end up
                    looking foolish. Note that images are not copied.
                </p>
                <input-field
                    class="my-6"
                    v-model="formData.name"
                    :error-msg="formErrors.name"
                    label="Name for copy"
                ></input-field>
                <submit-button :waiting="waiting" mode="dark"
                    >Copy Meal</submit-button
                >
            </form>
        </div>
    </div>
</template>

<script type="text/babel">
import SubmitButton from "../UI/SubmitButton";
import { showError, showSuccess } from "../../../libs/notifications";
import {
    clearValidationErrors,
    setValidationErrors,
} from "../../../libs/forms";
import InputField from "../Forms/InputField";

export default {
    components: { InputField, SubmitButton },

    props: ["meal"],

    data() {
        return {
            waiting: false,
            formData: { name: "" },
            formErrors: { name: "" },
        };
    },

    methods: {
        submit() {
            this.waiting = true;
            this.formErrors = clearValidationErrors(this.formErrors);

            this.$store
                .dispatch("meals/copy", {
                    meal_id: this.meal.id,
                    name: this.formData.name,
                })
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        onSuccess() {
            this.$router.push("/meals");
            showSuccess("Meal copied");
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            showError("Failed to copy meal");
        },
    },
};
</script>
