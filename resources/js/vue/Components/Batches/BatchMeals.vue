<template>
    <div>
        <p class="text-xl font-bold mb-8">Meal List</p>
        <div v-for="meal in meals" :key="meal.id" class="mb-6">
            <p class="font-bold">{{ meal.name }}</p>
            <div v-for="serving in meal.servings">
                <p>{{ serving.count }} x {{ serving.size }} people</p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    computed: {
        meals() {
            return this.$store.getters["menus/current_meals"];
        },
    },
};
</script>
