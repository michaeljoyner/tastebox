<template>
    <page>
        <page-header title="Notes">
            <button @click="showNoteModal = true" class="btn btn-main">
                Add Note
            </button>
        </page-header>

        <div class="my-12">
            <p class="text-gray-500" v-show="notes.length === 0">
                No notable notes have been noted for this meal
            </p>
            <div
                v-for="note in notes"
                :key="note.id"
                class="shadow-lg rounded-lg overflow-hidden pb-6 my-6"
            >
                <div
                    class="flex justify-between items-center bg-gray-800 text-white px-4 py-2"
                >
                    <p class="font-semibold text-lg">{{ note.title }}</p>
                    <p class="text-gray-200 text-sm">{{ note.created_at }}</p>
                </div>
                <p class="text-gray-500 text-sm my-3 px-2">{{ note.author }}</p>
                <div class="p-2" v-html="note.body"></div>
            </div>
        </div>

        <modal :show="showNoteModal" @close="showNoteModal = false">
            <div class="w-full max-w-2xl mx-auto bg-white p-6 rounded-lg">
                <p class="font-semibold text-2xl">Add a new Note</p>

                <form @submit.prevent="create">
                    <input-field
                        label="Title"
                        v-model="form.data.title"
                    ></input-field>

                    <div class="my-6">
                        <span>Body</span>
                        <editor
                            :compact="true"
                            v-model="form.data.body"
                            height="300px"
                        ></editor>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="showNoteModal = false">
                            Cancel
                        </button>
                        <submit-button :waiting="creating"
                            >Add Note</submit-button
                        >
                    </div>
                </form>
            </div>
        </modal>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { computed, ref } from "vue";
import { useForm } from "../../../libs/useForm";
import { useStore } from "vuex";
import { showError, showSuccess } from "../../../libs/notifications";
import Modal from "../../Components/Modal";
import Editor from "../../Components/Editor";
import SubmitButton from "../../Components/UI/SubmitButton";
import InputField from "../../Components/Forms/InputField";
export default {
    components: { InputField, SubmitButton, Editor, Modal, PageHeader, Page },
    props: ["meal"],

    setup(props) {
        const store = useStore();

        const notes = computed(() =>
            props.meal.notes.sort((a, b) => b.timestamp - a.timestamp)
        );

        const { form } = useForm({ title: "", body: "" });

        const showNoteModal = ref(false);

        const creating = ref(false);
        const create = () => {
            creating.value = true;
            store
                .dispatch("meals/addNote", {
                    meal_id: props.meal.id,
                    formData: form.data,
                })
                .then(() => {
                    showSuccess("Note has been saved");
                    form.data = { title: "", body: "" };
                    showNoteModal.value = false;
                })
                .catch((resp) => {
                    if (resp.status === 422) {
                        return showError("Your input is not valid");
                    }
                    showError("Failed to save note");
                })
                .then(() => (creating.value = false));
        };
        return { creating, create, showNoteModal, form, notes };
    },
};
</script>
