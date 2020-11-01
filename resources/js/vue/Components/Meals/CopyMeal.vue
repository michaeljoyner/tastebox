<template>
    <span>
        <button @click="showModal = true" class="btn">Copy</button>
        <modal :show="showModal" @close="showModal = false">
            <form @submit.prevent="submit" class="max-w-md w-full p-6">
                <p class="font-bold text-lg">Copy This Meal</p>
                <p class="my-4 text-sm">
                    You are about to make a copy of {{ meal.name }}. Do not
                    forget to update the necessary parts or you will end up
                    looking foolish. Note that images are not copied.
                </p>
                <div>
                    <text-field
                        v-model="formData.name"
                        :error-msg="formErrors.name"
                        label="Meal name"
                        placeholder="Name for the new meal"
                    ></text-field>
                </div>
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="btn mr-4"
                    >
                        Cancel
                    </button>
                    <submit-button mode="dark" :waiting="waiting"
                        >Do it</submit-button
                    >
                </div>
            </form>
        </modal>
    </span>
</template>

<script type="text/babel">
import SubmitButton from "../UI/SubmitButton";
import TextField from "../Forms/TextField";
import Modal from "@dymantic/modal";

export default {
    components: { TextField, SubmitButton, Modal },

    props: ["meal"],

    data() {
        return {
            waiting: false,
            showModal: false,
            formData: { name: "" },
            formErrors: { name: "" },
        };
    },

    methods: {
        submit() {
            this.waiting = true;

            this.$store
                .dispatch("meals/copy", {
                    meal_id: this.meal.id,
                    name: this.formData.name,
                })
                .then(this.onSuccess)
                .catch(this.onError)
                .then(() => (this.waiting = false));
        },
    },
};
</script>
