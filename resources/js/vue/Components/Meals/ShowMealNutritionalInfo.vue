<template>
    <div>
        <sub-header title="Nutritional Info">
            <router-link
                class="btn btn-main"
                :to="`/meals/${meal.id}/manage/nutritional-info/edit`"
                >Edit</router-link
            >
        </sub-header>
        <div class="my-12">
            <nutritional-info class="w-64 pt-6" :meal="meal"></nutritional-info>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader.vue";
import NutritionalInfo from "./NutritionalInfo.vue";
export default {
    components: { SubHeader, NutritionalInfo },

    props: ["meal"],
};
</script>
