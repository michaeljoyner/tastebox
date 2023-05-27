<template>
    <page v-if="meal">
        <page-header :title="meal.name"> </page-header>
        <div class="flex">
            <div class="w-64 h-64 pt-6 sticky top-0">
                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/info`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >General Info</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/ingredients`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Ingredients</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/nutritional-info`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Nutritional Info</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/instructions`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Instructions</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/public-recipe-notes`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Public Recipe Notes</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/photos`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Photos</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/actions`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Actions</router-link
                    >
                </div>

                <div class="mb-2">
                    <router-link
                        :to="`/meals/${$route.params.meal}/manage/notes`"
                        class="font-bold hover:text-blue-600"
                        active-class="text-blue-600"
                        >Notes</router-link
                    >
                </div>
            </div>
            <div class="flex-1">
                <router-view :meal="meal"></router-view>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";

export default {
    components: {
        Page,
        PageHeader,
    },

    computed: {
        meal() {
            return this.$store.getters["meals/byId"](this.$route.params.meal);
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals");
    },
};
</script>
