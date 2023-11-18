<template>
    <div>
        <SubHeader title="Add New Costing">
            <RouterLink
                :to="`/meals/${meal.id}/manage/costings`"
                class="muted-text-btn"
                >&larr; Back</RouterLink
            >
        </SubHeader>

        <div>
            <MealCostingForm
                :meal-id="meal.id"
                @saved="$router.push(`/meals/${meal.id}/manage/costings`)"
            />
        </div>
    </div>
</template>

<script setup>
import SubHeader from "../../Components/UI/SubHeader.vue";
import MealCostingForm from "../../Components/Meals/MealCostingForm.vue";

const props = defineProps({ meal: Object });
</script>
