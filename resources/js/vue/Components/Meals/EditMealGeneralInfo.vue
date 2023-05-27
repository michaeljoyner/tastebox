<template>
    <div>
        <sub-header title="Edit Meal Info">
            <router-link
                :to="`/meals/${$route.params.meal}/manage/info`"
                class="btn"
                >Back</router-link
            >
        </sub-header>

        <meal-info-form :meal="meal"></meal-info-form>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader.vue";
import MealInfoForm from "./MealInfoForm.vue";
export default {
    components: { MealInfoForm, SubHeader },

    props: ["meal"],
};
</script>
