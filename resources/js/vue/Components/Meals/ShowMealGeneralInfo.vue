<template>
    <div>
        <sub-header title="General Info">
            <router-link
                :to="`/meals/${$route.params.meal}/manage/info/edit`"
                class="btn btn-main"
                >Edit</router-link
            >
        </sub-header>
        <div>
            <span
                class="px-2 py-1 rounded-lg font-bold border-2 border-black text-sm mr-4"
                v-for="classification in meal.classifications"
                :key="classification.id"
            >
                {{ classification.name }}
            </span>
            <meal-times class="my-12" :meal="meal"></meal-times>
            <p class="max-w-lg text-lg mt-8">{{ meal.description }}</p>
            <p class="my-6"><strong>Allergens: </strong>{{ meal.allergens }}</p>

            <div>
                <p class="font-bold mb-4">Order Stats:</p>
                <div class="flex justify-between flex-wrap">
                    <div>
                        <p class="font-semibold">Last available:</p>
                        <p class="">{{ meal.last_offered_ago }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Times on offer:</p>
                        <p class="">{{ meal.times_offered }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Included in kit:</p>
                        <p class="">{{ meal.total_ordered }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Total servings ordered:</p>
                        <p class="">{{ meal.total_servings }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import MealTimes from "./MealTimes";
import SubHeader from "../UI/SubHeader";
export default {
    components: { SubHeader, MealTimes },

    props: ["meal"],
};
</script>
