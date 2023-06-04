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

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { onMounted, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const store = useStore();
const route = useRoute();

const meal = computed(() => store.state.meals.active);

onMounted(() => {
    store.dispatch("meals/fetchActive", route.params.meal);
});
</script>
