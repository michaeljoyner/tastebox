<template>
    <div
        v-show="totalPages > 1"
        class="flex justify-end space-x-3 text-sm text-gray-500 mt-6"
    >
        <button :disabled="currentPage === 1" @click="prev">&larr; Prev</button>
        <div>
            Page {{ currentPage }} of
            {{ totalPages }}
        </div>
        <button :disabled="currentPage >= totalPages" @click="next">
            Next &rarr;
        </button>
    </div>
</template>

<script setup>
import { useStore } from "vuex";
import { computed } from "vue";

const store = useStore();

const currentPage = computed(() => store.state.meals.page);
const totalPages = computed(() => store.state.meals.total_pages);

const prev = () => store.dispatch("meals/getPrevPage");
const next = () => store.dispatch("meals/getNextPage");
</script>
