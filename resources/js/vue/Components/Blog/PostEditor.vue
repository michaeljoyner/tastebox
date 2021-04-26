<template>
    <div
        class="flex flex-col justify-between"
        style="height: calc(100vh - 4rem);"
    >
        <h1 class="px-6 my-8 text-3xl font-semibold">
            {{ form.data.title || `[Untitled]` }}
        </h1>
        <div class="flex flex-1">
            <div class="w-80 px-6">
                <input-field
                    class="mb-6"
                    label="Post title"
                    :error-msg="form.errors.title"
                    v-model="form.data.title"
                ></input-field>
                <text-area-field
                    class="mb-6"
                    label="Description"
                    help-text="A brief description for Google"
                    v-model="form.data.description"
                ></text-area-field>
                <text-area-field
                    class="mb-6"
                    label="Introduction"
                    help-text="Introduction for the main blog page, etc"
                    v-model="form.data.intro"
                ></text-area-field>
            </div>
            <div class="flex-1 px-6">
                <editor v-model="form.data.body"></editor>
            </div>
        </div>
        <div class="h-12 flex justify-between">
            <div></div>
            <div>
                <button type="button" @click="savePost">Save</button>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import { reactive } from "vue";
import TextAreaField from "../Forms/TextAreaField";
import InputField from "../Forms/InputField";
import Editor from "../Editor";
import { useStore } from "vuex";
import { useForm } from "../../../libs/useForm";
export default {
    components: { Editor, InputField, TextAreaField },

    setup() {
        const { form, handleFormError, clearFormErrors } = useForm({
            title: "",
            intro: "",
            description: "",
            body: "",
        });

        const store = useStore();

        const savePost = () => {
            clearFormErrors();
            store
                .dispatch("blog/create", form.data)
                .then(() => showSuccess("Hey presto"))
                .catch((resp) => handleFormError(resp, "failed to save post"));
        };

        return { form, savePost };
    },
};
</script>
