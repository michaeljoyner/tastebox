<template>
    <div>
        <div class="flex justify-between items-center mb-8">
            <p class="text-xl font-bold">Shopping List</p>
            <button class="btn btn-main" @click="downloadShoppingList">
                Shopping List
            </button>
        </div>

        <div class="my-12">
            <div v-for="item in list" :key="item.id" class="mb-6 shadow p-4">
                <p class="font-bold capitalize">{{ item.item_name }}</p>
                <div class="border-b-2 border-green-600 w-40 mt-2 mb-4"></div>
                <p
                    v-for="(qty, unit) in item.amounts"
                    :key="unit"
                    class="text-3xl"
                >
                    <span>{{ qty }}</span>
                    <span>{{ unit === "x_unit" ? "" : unit }}</span>
                </p>
                <div>
                    <p class="text-sm" v-for="use in item.uses">{{ use }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
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
