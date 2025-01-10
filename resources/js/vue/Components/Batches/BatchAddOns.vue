<template>
    <div>
        <p class="text-xl font-bold mb-8">Add-Ons List</p>

        <p v-show="!addOns.length">No add-ons have been ordered in this batch.</p>

        <div v-for="add_on in addOns" :key="add_on.id" class="mb-6">
            <p class="font-bold">{{ add_on.name }} x {{ add_on.qty}}</p>

        </div>
    </div>
</template>

<script type="text/babel">
export default {
    computed: {
        addOns() {
            return this.$store.getters["menus/current_add_ons"];
        },
    },
};
</script>
