<template>
    <div>
        <div class="flex justify-between items-center mb-8">
            <p class="text-xl font-bold">Ingredient List</p>
            <button class="btn btn-main" @click="downloadShoppingList">
                Shopping List
            </button>
        </div>

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

        menu_id() {
            return this.$store.getters["menus/current_batch_menu_id"];
        },
    },

    methods: {
        downloadShoppingList() {
            window.location = `/admin/api/menus/${this.menu_id}/batch/shopping-list`;
        },
    },
};
</script>
