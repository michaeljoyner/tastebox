<template>
    <div>
        <sub-header title="Cooking Instructions">
            <router-link
                class="btn btn-main"
                :to="`/meals/${meal.id}/manage/instructions/edit`"
                >Edit</router-link
            >
        </sub-header>

        <div class="my-12">
            <div v-html="meal.instructions"></div>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader";
export default {
    components: { SubHeader },

    props: ["meal"],
};
</script>
