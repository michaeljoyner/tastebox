<template>
    <div class="shadow divide-y divide-gray-200">
        <div v-for="meal in meals" :key="meal.id" class="">
            <router-link
                :to="`/meals/${meal.id}`"
                class="text-gray-800 hover:text-pink-500 flex"
            >
                <img :src="meal.title_image.thumb" class="w-16" />
                <div class="p-4 flex flex-1 justify-between">
                    <p class="font-bold">{{ meal.name }}</p>
                    <div>
                        <span
                            class="font-bold text-xs uppercase rounded-lg border-2 border-black px-2 py-1 mx-4"
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
