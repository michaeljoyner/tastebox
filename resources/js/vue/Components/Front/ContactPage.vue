<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-6">
                <label class="text-sm text-gray-800 font-semibold" for="name"
                    >Your name</label
                >
                <p class="text-xs text-red-600 my-1" v-show="formErrors.name">
                    {{ formErrors.name }}
                </p>
                <input
                    class="block mt-1 border border-gray-300 rounded p-2 w-full"
                    type="text"
                    name="name"
                    id="name"
                    v-model="formData.name"
                />
            </div>

            <div class="my-6">
                <label class="text-sm text-gray-800 font-semibold" for="name"
                    >Your email address</label
                >
                <p class="text-xs text-red-600 my-1" v-show="formErrors.email">
                    {{ formErrors.email }}
                </p>
                <input
                    class="block mt-1 border border-gray-300 rounded p-2 w-full"
                    type="email"
                    name="email"
                    id="email"
                    v-model="formData.email"
                />
            </div>

            <div class="my-6">
                <label class="text-sm text-gray-800 font-semibold" for="phone"
                    >Your phone number</label
                >
                <p class="text-xs text-red-600 my-1" v-show="formErrors.phone">
                    {{ formErrors.phone }}
                </p>
                <input
                    class="block mt-1 border border-gray-300 rounded p-2 w-full"
                    type="text"
                    name="phone"
                    id="phone"
                    v-model="formData.phone"
                />
            </div>

            <div class="my-6">
                <label class="text-sm text-gray-800 font-semibold" for="message"
                    >Your message</label
                >
                <p
                    class="text-xs text-red-600 my-1"
                    v-show="formErrors.message"
                >
                    {{ formErrors.message }}
                </p>
                <textarea
                    class="block mt-1 border border-gray-300 rounded p-2 w-full h-32"
                    id="message"
                    name="message"
                    v-model="formData.message"
                ></textarea>
            </div>

            <div class="my-10 flex justify-center">
                <submit-button :waiting="waiting">Send Message</submit-button>
            </div>
        </form>
        <modal :show="showSuccessModal" @close="showSuccessModal = false">
            <div class="max-w-lg w-full p-6">
                <p class="text-lg text-center text-green-600 font-bold">
                    Thank You!
                </p>
                <p class="my-6 text-gray-800 text-center">
                    Your message has been sent, and we will get to it as soon as
                    possible. Thanks for getting in touch.
                </p>
                <div class="mt-6 text-center">
                    <button
                        class="font-bold"
                        type="button"
                        @click="showSuccessModal = false"
                    >
                        Okay
                    </button>
                </div>
            </div>
        </modal>

        <modal :show="showErrorModal" @close="showErrorModal = false">
            <div class="max-w-lg w-full rounded-lg p-6">
                <p class="text-lg text-center text-red-600 font-bold">
                    Oh dear :(
                </p>
                <p class="my-6 text-gray-800 text-center">
                    Something went wrong, and your message couldn't be sent.
                    It's not you, it's us. Please refresh your page and try
                    again later. Thanks
                </p>
                <div class="mt-6 text-center">
                    <button
                        class="font-bold"
                        type="button"
                        @click="showErrorModal = false"
                    >
                        Okay
                    </button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">
import SubmitButton from "./SubmitButton";
import Modal from "@dymantic/modal";
export default {
    components: {
        SubmitButton,
        Modal,
    },

    data() {
        return {
            showSuccessModal: false,
            showErrorModal: false,
            waiting: false,
            formData: {
                name: "",
                email: "",
                phone: "",
                message: "",
            },
            formErrors: {
                name: "",
                email: "",
                phone: "",
                message: "",
            },
        };
    },

    methods: {
        submit() {
            this.waiting = true;
            this.clearValidationErrors();

            axios
                .post("/contact", this.formData)
                .then(this.onSuccess)
                .catch(({ response }) => this.onError(response))
                .then(() => (this.waiting = false));
        },

        onSuccess() {
            this.showSuccessModal = true;
            this.clearForm();
        },

        onError({ status, data }) {
            if (status === 422) {
                return this.setValidationErrors(data.errors);
            }
            this.showErrorModal = true;
        },

        setValidationErrors(errors) {
            Object.keys(errors).forEach((key) => {
                if (this.formErrors.hasOwnProperty(key)) {
                    this.formErrors[key] = errors[key][0];
                }
            });
        },

        clearValidationErrors() {
            this.formErrors = {
                name: "",
                email: "",
                phone: "",
                message: "",
            };
        },

        clearForm() {
            this.formData = {
                name: "",
                email: "",
                phone: "",
                message: "",
            };
        },
    },
};
</script>
