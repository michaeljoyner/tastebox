<template>
    <form @submit.prevent="submit">
        <div class="my-12">
            <p class="mb-6 font-bold">Custome details</p>
            <div class="flex">
                <input-field
                    class="mr-6 flex-1"
                    label="First Name"
                    v-model="formData.first_name"
                    :error-msg="formErrors.first_name"
                ></input-field>
                <input-field
                    class="ml-6 flex-1"
                    label="Surname"
                    v-model="formData.last_name"
                    :error-msg="formErrors.last_name"
                ></input-field>
            </div>

            <input-field
                class="my-6"
                type="email"
                label="Email address"
                :error-msg="formErrors.email"
                v-model="formData.email"
            ></input-field>

            <input-field
                class="my-6"
                label="Cell Number"
                :error-msg="formErrors.phone"
                v-model="formData.phone"
            ></input-field>

            <input-field
                class="my-6"
                label="Address (line one)"
                :error-msg="formErrors.line_one"
                v-model="formData.line_one"
            ></input-field>

            <input-field
                class="my-6"
                label="Address (line two)"
                :error-msg="formErrors.line_two"
                v-model="formData.line_two"
            ></input-field>

            <input-field
                class="my-6"
                label="City/Town/Area"
                placeholder="e.g. Hilton, Camperdown"
                :error-msg="formErrors.city"
                v-model="formData.city"
            ></input-field>
        </div>

        <div class="my-12">
            <p class="mb-8">Meals</p>
            <div class="divide-y divide-gray-400 space-y-3">
                <div
                    v-for="meal in formData.meals"
                    :key="meal.id"
                    class="pt-3 flex justify-between items-center"
                >
                    <p
                        :class="{
                            'font-bold text-green-600': meal.servings > 0,
                        }"
                    >
                        {{ meal.name }}
                    </p>
                    <up-downer
                        :options="[0, 1, 2, 4, 6]"
                        v-model="meal.servings"
                    ></up-downer>
                </div>
            </div>
        </div>
        <div class="my-12">
            <submit-button :waiting="waiting">Place Order</submit-button>
        </div>
    </form>
</template>

<script type="text/babel">
import InputField from "../Forms/InputField";
import { showError, showSuccess } from "../../../libs/notifications";
import UpDowner from "../Forms/UpDowner";
import {
    clearValidationErrors,
    setValidationErrors,
} from "../../../libs/forms";
import SubmitButton from "../UI/SubmitButton";
export default {
    components: { SubmitButton, UpDowner, InputField },
    props: ["available-meals"],
    data() {
        return {
            waiting: false,
            formData: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
                line_one: "",
                line_two: "",
                city: "",
                meals: this.availableMeals.map((m) => ({
                    name: m.name,
                    id: m.id,
                    servings: 0,
                })),
            },
            formErrors: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
                line_one: "",
                line_two: "",
                city: "",
            },
        };
    },

    methods: {
        submit() {
            this.waiting = true;
            this.formErrors = clearValidationErrors(this.formErrors);

            const fd = this.formattedData();

            this.$store
                .dispatch("menus/manualOrder", fd)
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },

        formattedData() {
            const data = { ...this.formData };
            data.meals = data.meals
                .map((m) => ({ id: m.id, servings: m.servings }))
                .filter((m) => m.servings > 0);
            return data;
        },

        onSuccess() {
            showSuccess("Order placed");
            this.$router.push("/current-batch");
        },

        onError({ status, data }) {
            if (status === 422) {
                return (this.formErrors = setValidationErrors(
                    this.formErrors,
                    data.errors
                ));
            }
            showError("Failed to place order");
        },
    },
};
</script>
