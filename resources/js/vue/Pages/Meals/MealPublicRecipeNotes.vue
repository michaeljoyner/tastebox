<template>
    <div>
        <sub-header title="Public Recipe Notes">
            <router-link
                :to="`/meals/${meal.id}/manage/public-recipe-notes/edit`"
                >Edit</router-link
            >
        </sub-header>

        <div class="my-12">
            <p class="text-gray-500" v-show="!meal.public_recipe_notes">
                No public notes have been added for this meal's recipe
            </p>

            <div
                v-show="meal.public_recipe_notes"
                v-html="meal.public_recipe_notes"
                class="prose"
            ></div>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../../Components/UI/SubHeader.vue";
export default {
    components: { SubHeader },

    props: ["meal"],

    setup(props) {},
};
</script>
