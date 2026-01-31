<template>
    <div>
        <sub-header title="General Info">
            <router-link
                :to="`/meals/${$route.params.meal}/manage/info/edit`"
                class="btn btn-main"
                >Edit</router-link
            >
        </sub-header>

        <div
            class="my-12 font-bold inline-block px-4 py-2 rounded-md"
            :class="tierColour"
        >
            {{ meal.tier }} | R{{ meal.price }}
        </div>

        <div>
            <span
                class="px-2 py-1 rounded-lg font-bold border-2 border-black text-sm mr-4"
                v-for="classification in meal.classifications"
                :key="classification.id"
            >
                {{ classification.name }}
            </span>
            <meal-times class="my-12" :meal="meal"></meal-times>

            <p class="text-xs font-bold">Description:</p>
            <div class="flex gap-3 border-b border-indigo-500 mb-3 mt-1">
                <button
                    class="text-xs uppercase px-3 py-1 border-indigo-500 border-b-0"
                    :class="{
                        'border bg-indigo-400 text-white':
                            descriptionType === 'web',
                    }"
                    @click="setDescriptionType('web')"
                >
                    Website
                </button>
                <button
                    class="text-xs uppercase px-3 py-1 border-indigo-500 border-b-0"
                    :class="{
                        'border bg-indigo-400 text-white':
                            descriptionType === 'card',
                    }"
                    @click="setDescriptionType('card')"
                >
                    Recipe Card
                </button>
            </div>

            <p v-show="descriptionType === 'web'" class="max-w-lg mt-8">
                {{ meal.description }}
            </p>
            <p v-show="descriptionType === 'card'" class="max-w-lg mt-8">
                {{ meal.meal_card_description }}
            </p>
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

<script setup>
import MealTimes from "./MealTimes.vue";
import SubHeader from "../UI/SubHeader.vue";
import { computed, ref } from "vue";

const props = defineProps({ meal: Object });

const tierColour = computed(() => {
    const lookup = {
        1: "bg-red-300",
        2: "bg-green-300",
        3: "bg-yellow-400",
        4: "bg-black text-white",
    };
    return lookup[props.meal.tier_value] || "bg-blue-400";
});

const descriptionType = ref("web");
const setDescriptionType = (type) => {
    if (type === "card") {
        return (descriptionType.value = "card");
    }

    descriptionType.value = "web";
};
</script>
