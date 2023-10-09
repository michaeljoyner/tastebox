<template>
    <div class="">
        <div
            class="flex items-center space-x-2 px-6 rounded-full border focus-within:ring-1 max-w-sm mb-6"
        >
            <SearchIcon class="w-5 h-5 text-gray-400" />
            <input
                type="text"
                v-model="search"
                class="focus:ring-0 focus:outline-none border-0"
                placeholder="Search ingredients"
            />
        </div>
        <div
            v-for="item in matchingItems"
            :key="item.id"
            class="mb-6 shadow p-4"
        >
            <p class="font-bold capitalize">{{ item.item_name }}</p>
            <div class="border-b-2 border-pink-600 w-40 mt-2 mb-4"></div>
            <p v-for="(qty, unit) in item.amounts" :key="unit" class="text-3xl">
                <span>{{ qty }}</span>
                <span>{{ unit === "x_unit" ? "" : unit }}</span>
            </p>
            <div>
                <p class="text-sm" v-for="use in item.uses">{{ use }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import SearchIcon from "../Icons/SearchIcon.vue";

const props = defineProps({ items: Array });

const search = ref("");
const matchingItems = computed(() => {
    if (search.value.length < 3) {
        return props.items;
    }

    return props.items.filter((i) =>
        i.item_name.toLowerCase().includes(search.value.toLowerCase())
    );
});
</script>
