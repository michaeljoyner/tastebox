<template>
    <div class="shadow divide-y divide-gray-200">
        <div v-for="meal in meals" :key="meal.id" class="p-2">
            <router-link
                :to="`/meals/${meal.id}/manage/info`"
                class="text-gray-800 hover:text-blue-600 flex"
            >
                <div class="w-12 h-12 rounded-full overflow-hidden">
                    <img
                        :src="meal.title_image.thumb"
                        class="w-full h-full object-cover"
                    />
                </div>

                <div class="pl-2 pb-2 flex-1 flex items-center justify-between">
                    <p class="w-full max-w-md truncate pr-6 font-semibold">
                        {{ meal.name || `[UNTITLED MEAL]` }}
                    </p>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({ meals: Array });
</script>
