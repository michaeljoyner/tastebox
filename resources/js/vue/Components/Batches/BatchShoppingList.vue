<template>
    <div>
        <div class="flex justify-between items-center mb-8">
            <p class="text-xl font-bold">Shopping List</p>
            <button class="btn btn-main" @click="downloadShoppingList">
                Shopping List
            </button>
        </div>

        <ShoppingListItems :items="list" />
    </div>
</template>

<script type="text/babel">
import ShoppingListItems from "../Meals/ShoppingListItems.vue";

export default {
    components: { ShoppingListItems },
    computed: {
        list() {
            const ing = this.$store.getters["menus/current_shopping_list"];

            return ing.sort((a, b) => {
                if (a.item_name.toLowerCase() < b.item_name.toLowerCase()) {
                    return -1;
                }
                if (a.item_name.toLowerCase() > b.item_name.toLowerCase()) {
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
