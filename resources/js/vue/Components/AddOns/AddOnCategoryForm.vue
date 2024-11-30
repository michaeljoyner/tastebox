<template>
    <form @submit.prevent="submit">
        <InputField
            class="my-6"
            label="Category name"
            :error-msg="form.errors.name"
            v-model="form.data.name"
        />

        <TextAreaField
            class="my-6"
            label="Description"
            :error="form.errors.description"
            v-model="form.data.description"
        />
        <div class="flex mt-6 justify-end items-center gap-4">
            <button type="button" @click="$emit('cancel')">Cancel</button>
            <SubmitButton :waiting="submitting"
                >{{
                    props.category ? "Update" : "Create"
                }}
                Category</SubmitButton
            >
        </div>
    </form>
</template>

<script setup>
import { useForm } from "../../../libs/useForm";
import { httpAction } from "../../../libs/httpAction";
import { useStore } from "vuex";
import { showError, showSuccess } from "../../../libs/notifications";
import InputField from "../Forms/InputField.vue";
import TextAreaField from "../Forms/TextAreaField.vue";
import SubmitButton from "../UI/SubmitButton.vue";

const props = defineProps({ category: Object });
const emit = defineEmits(["cancel", "done"]);

const store = useStore();

const { form, setFormErrors, clearFormErrors } = useForm({
    name: props.category?.name || "",
    description: props.category?.description || "",
});

const [submitting, submit] = httpAction(
    () => {
        clearFormErrors();
        const action = props.category
            ? "addons/updateCategory"
            : "addons/createCategory";
        const payload = props.category
            ? { uuid: props.category.uuid, formData: form.data }
            : form.data;
        return store.dispatch(action, payload);
    },
    () => {
        showSuccess("Category saved");
        emit("done");
    },
    (resp) => {
        console.log({ resp });
        if (resp.status === 422) {
            return setFormErrors(resp.data.errors);
        }
        showError("Failed to save category");
    }
);
</script>
