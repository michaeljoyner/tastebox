<template>
    <div>
        <p class="text-xl font-bold mb-8">Ingredient List</p>
        <div
            v-for="ingredient in ingredients"
            :key="ingredient.id"
            class="mb-4"
        >
            <p class="font-bold">{{ ingredient.description }}</p>
            <div v-for="use in ingredient.uses" class="pl-6">
                <p class="max-w-lg truncate">
                    {{ use.count }} x {{ use.quantity }} ({{ use.meal }})
                </p>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    computed: {
        ingredients() {
            const ing = this.$store.getters["menus/current_ingredients"];

            return ing.sort((a, b) => {
                if (a.description < b.description) {
                    return -1;
                }
                if (a.description > b.description) {
                    return 1;
                }
                return 0;
            });
        },
    },
};
</script>
