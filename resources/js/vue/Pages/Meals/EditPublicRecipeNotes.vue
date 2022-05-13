<template>
    <div>
        <sub-header title="Edit Public Recipe Notes">
            <router-link :to="`/meals/${meal.id}/manage/public-recipe-notes`"
                >&larr; Back</router-link
            >
        </sub-header>

        <div class="my-12">
            <editor class="list-disc" v-model="notes"></editor>
        </div>

        <div>
            <submit-button :waiting="saving" @click="save">Save</submit-button>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../../Components/UI/SubHeader";
import { useStore } from "vuex";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import { useRouter } from "vue-router";
import { ref } from "vue";
import Editor from "../../Components/Editor";
import SubmitButton from "../../Components/UI/SubmitButton";
export default {
    components: { SubmitButton, Editor, SubHeader },

    props: ["meal"],

    setup(props) {
        const store = useStore();
        const router = useRouter();

        const notes = ref(props.meal.public_recipe_notes);

        const [saving, save] = httpAction(
            () =>
                store.dispatch("meals/updatePublicRecipeNotes", {
                    meal_id: props.meal.id,
                    public_recipe_notes: notes.value,
                }),
            () => {
                showSuccess("Recipe notes updated");
                router.push(
                    `/meals/${props.meal.id}/manage/public-recipe-notes`
                );
            },
            () => showError("Failed to update notes")
        );

        return { notes, saving, save };
    },
};
</script>
