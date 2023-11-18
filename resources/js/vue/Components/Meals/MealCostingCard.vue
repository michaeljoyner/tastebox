<template>
    <div class="p-3 rounded-md shadow-md mb-6 bg-blue-50">
        <div class="flex space-x-4 items-center">
            <p class="text-sm font-semibold w-32">
                {{ costing.date_costed_pretty }}
            </p>
            <p class="text-lg font-black">{{ costing.cost }}</p>
            <p>
                {{
                    $store.getters["meals/priceTierDescription"](
                        costing.tier_value
                    )
                }}
            </p>
            <div class="flex-1 flex justify-end items-center space-x-3">
                <RouterLink
                    :to="`/meals/${mealId}/manage/costings/${costing.id}/edit`"
                    class="muted-text-btn"
                    >Edit</RouterLink
                >
            </div>
        </div>
        <div v-show="costing.note" class="border-t border-blue-200 pt-2 mt-2">
            <p class="text-sm">{{ costing.note }}</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({ costing: Object, mealId: Number });
</script>
