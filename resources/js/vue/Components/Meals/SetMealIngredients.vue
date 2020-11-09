<template>
    <div>
        <sub-header title="Meal Ingredients">
            <submit-button
                :waiting="waiting"
                mode="dark"
                role="button"
                @click.native="save"
                >Save</submit-button
            >
        </sub-header>

        <div class="my-12">
            <ingredient-list v-model="ingredients"></ingredient-list>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader";
import IngredientList from "./IngredientList";
import SubmitButton from "../UI/SubmitButton";
import { showError, showSuccess } from "../../../libs/notifications";
export default {
    components: { IngredientList, SubHeader, SubmitButton },

    props: ["meal"],

    data() {
        return {
            waiting: false,
            ingredients: this.meal.ingredients,
        };
    },

    mounted() {
        this.$store.dispatch("meals/fetchIngredients");
    },

    methods: {
        save() {
            this.waiting = true;
            this.$store
                .dispatch("meals/updateIngredients", {
                    meal_id: this.meal.id,
                    ingredients: this.ingredients,
                })
                .then(() => showSuccess("Saved ingredients"))
                .catch(() => showError("Failed to save ingredients"))
                .then(() => (this.waiting = false));
        },
    },
};
</script>
