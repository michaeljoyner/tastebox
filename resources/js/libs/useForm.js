import { reactive } from "vue";
import {
    createFieldsWithData,
    createMessageBag,
    fillErrorBag,
} from "./form-helpers";
import { showError } from "./notifications";

function useForm(structure, fields, errors) {
    const data_fields = fields
        ? createFieldsWithData(structure, fields)
        : structure;
    const error_structure = errors ? errors : structure;
    const form = reactive({
        data: data_fields,
        errors: createMessageBag(error_structure),
    });

    const setFormErrors = (errorBag) => {
        form.errors = fillErrorBag(errorBag, form.errors);
    };

    const clearFormErrors = () => {
        form.errors = createMessageBag(error_structure);
    };

    const handleFormError = (resp, message) => {
        if (resp.status === 422) {
            return setFormErrors(resp.data.errors);
        }
        showError(message);
    };

    return {
        form,
        handleFormError,
        setFormErrors,
        clearFormErrors,
    };
}

export { useForm };
