<template>
    <div class="shadow divide-y divide-gray-200">
        <div v-for="meal in meals" :key="meal.id" class="">
            <router-link
                :to="`/meals/${meal.id}`"
                class="text-gray-800 hover:text-blue-600 flex"
            >
                <div class="w-20 h-auto">
                    <img
                        :src="meal.title_image.thumb"
                        class="w-full h-full object-cover"
                    />
                </div>

                <div class="pl-2 pb-2 flex-1 justify-between">
                    <p class="w-full max-w-md truncate pr-6">
                        {{ meal.name }}
                    </p>
                    <div class="mt-2">
                        <span
                            class="text-xs rounded-lg border border-black p-1 mr-4"
                            v-for="type in meal.classifications"
                            :key="type.id"
                            >{{ type.name }}</span
                        >
                    </div>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["meals"],
};
</script>
