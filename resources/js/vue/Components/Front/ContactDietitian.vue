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
                <p class="text-sm text-gray-800 font-semibold">
                    How can I reach you?
                </p>
                <p class="text-sm text-gray-600 mb-3">
                    Leave either your email or phone, and I will get in touch
                </p>
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
                <label
                    class="text-sm text-gray-800 font-semibold"
                    for="location"
                    >Where are you based?</label
                >
                <p class="text-sm text-gray-600 mb-3">
                    Letting me know whereabouts you live helps figure out what
                    options we have.
                </p>
                <p
                    class="text-xs text-red-600 my-1"
                    v-show="formErrors.location"
                >
                    {{ formErrors.location }}
                </p>
                <input
                    class="block mt-1 border border-gray-300 rounded p-2 w-full"
                    type="text"
                    name="location"
                    id="location"
                    v-model="formData.location"
                />
            </div>

            <div class="my-6">
                <label class="text-sm text-gray-800 font-semibold" for="message"
                    >Your message</label
                >
                <p class="text-sm text-gray-600 mb-3">
                    Let me know what you are looking for, and whether you have
                    any specific dates or times in mind.
                </p>
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
            <div class="max-w-lg mx-auto bg-white w-full p-6">
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
            <div class="max-w-lg mx-auto bg-white w-full rounded-lg p-6">
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

<script setup>
import SubmitButton from "./SubmitButton.vue";
import Modal from "../Modal.vue";
import { reactive, ref } from "vue";

const formData = reactive({
    name: "",
    email: "",
    phone: "",
    location: "",
    message: "",
});

const formErrors = reactive({
    name: "",
    email: "",
    phone: "",
    location: "",
    message: "",
});

const showSuccessModal = ref(false);
const showErrorModal = ref(false);
const waiting = ref(false);

const submit = () => {
    waiting.value = true;
    clearValidationErrors();

    axios
        .post("/contact-dietitian", formData)
        .then(onSuccess)
        .catch(({ response }) => onError(response))
        .then(() => (waiting.value = false));
};

const onSuccess = () => {
    showSuccessModal.value = true;
    clearForm();
};

const onError = ({ status, data }) => {
    if (status === 422) {
        return setValidationErrors(data.errors);
    }
    showErrorModal.value = true;
};

const setValidationErrors = (errors) => {
    Object.keys(errors).forEach((key) => {
        if (formErrors.hasOwnProperty(key)) {
            formErrors[key] = errors[key][0];
        }
    });
};

const clearValidationErrors = () => {
    formErrors.name = "";
    formErrors.email = "";
    formErrors.phone = "";
    formErrors.location = "";
    formErrors.message = "";
};

const clearForm = () => {
    formData.name = "";
    formData.email = "";
    formData.phone = "";
    formData.location = "";
    formData.message = "";
};
</script>
