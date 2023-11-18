<template>
    <SubHeader title="Costings">
        <RouterLink
            :to="`/meals/${meal.id}/manage/costings/add`"
            class="muted-text-btn"
            >+ New Costing</RouterLink
        >
    </SubHeader>

    <div>
        <p v-show="!meal.costings.length" class="text-gray-500">
            There are no costings for this meal yet.
        </p>
        <MealCostingCard
            v-for="costing in meal.costings"
            :costing="costing"
            :meal-id="meal.id"
            :key="costing.id"
        />
    </div>
</template>

<script setup>
import SubHeader from "../../Components/UI/SubHeader.vue";
import MealCostingCard from "../../Components/Meals/MealCostingCard.vue";

const props = defineProps({ meal: Object });
</script>
