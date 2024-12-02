<template>
    <form @submit.prevent="submit">
        <InputField
            class="my-6"
            label="Add On name"
            :error-msg="form.errors.name"
            v-model="form.data.name"
        />

        <TextAreaField
            class="my-6"
            label="Description"
            :error="form.errors.description"
            v-model="form.data.description"
        />

        <InputField
            class="my-6"
            label="Price"
            :error-msg="form.errors.price"
            v-model="form.data.price"
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

const props = defineProps({ addon: Object, categoryUuid: String });
const emit = defineEmits(["cancel", "done"]);

const store = useStore();

const { form, setFormErrors, clearFormErrors } = useForm({
    name: props.addon?.name || "",
    description: props.addon?.description || "",
    price: (props.addon?.price || 0) / 100,
});

const getData = () => {
    return {
        name: form.data.name,
        description: form.data.description,
        price: form.data.price * 100,
    };
};

const [submitting, submit] = httpAction(
    () => {
        clearFormErrors();
        const action = props.addon
            ? "addons/updateAddOn"
            : "addons/createAddOn";
        const payload = props.addon
            ? { uuid: props.addon.uuid, formData: getData() }
            : { category_uuid: props.categoryUuid, formData: getData() };
        return store.dispatch(action, payload);
    },
    () => {
        showSuccess("Addon saved");
        emit("done");
    },
    (resp) => {
        console.log({ resp });
        if (resp.status === 422) {
            return setFormErrors(resp.data.errors);
        }
        showError("Failed to save addon");
    }
);
</script>
