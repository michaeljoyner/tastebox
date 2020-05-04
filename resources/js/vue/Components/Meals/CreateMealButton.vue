<template>
    <button @click="createMeal" class="btn btn-main">New Meal</button>
</template>

<script type="text/babel">
import { showSuccess, showError } from "../../../libs/notifications";

export default {
    methods: {
        createMeal() {
            this.$store
                .dispatch("meals/createMeal")
                .then(({ id }) => {
                    showSuccess("meal added");
                    this.$router.push(`/meals/${id}/edit`);
                })
                .catch(showError);
        },
    },
};
</script>
