<template>
    <div>
        <sub-header title="Edit Cooking Instructions">
            <router-link
                :to="`/meals/${meal.id}/manage/instructions`"
                class="btn mx-4"
                >Back</router-link
            >
            <submit-button
                :waiting="waiting"
                mode="dark"
                role="button"
                @click="submit"
                >Save</submit-button
            >
        </sub-header>

        <div class="my-12">
            <editor class="list-disc" v-model="instructions"></editor>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader";
import SubmitButton from "../UI/SubmitButton";
import Editor from "../Editor";
import { showError, showSuccess } from "../../../libs/notifications";
export default {
    components: { SubHeader, SubmitButton, Editor },

    props: ["meal"],

    data() {
        return {
            waiting: false,
            instructions: this.meal.instructions,
        };
    },

    methods: {
        submit() {
            this.waiting = true;

            this.$store
                .dispatch("meals/updateInstructions", {
                    meal_id: this.meal.id,
                    instructions: this.instructions,
                })
                .then(() => {
                    showSuccess("Cooking instructions updated.");
                    this.$router.push(
                        `/meals/${this.meal.id}/manage/instructions`
                    );
                })
                .catch(() => showError("Failed to update instructions"))
                .then(() => (this.waiting = false));
        },
    },
};
</script>
