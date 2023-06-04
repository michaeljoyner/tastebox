<template>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div
                    v-show="searched_classifications.length"
                    class="flex text-sm space-x-2 items-center text-gray-500"
                >
                    <p>Showing meals tagged as</p>
                    <span
                        class="text-white bg-indigo-500 p-1 text-xs rounded"
                        v-for="classification in searched_classifications"
                        :key="classification.id"
                        >{{ classification.name }}</span
                    >
                    <p v-show="$store.state.meals.search_query">
                        and matching "{{ $store.state.meals.search_query }}"
                    </p>
                </div>

                <p
                    v-show="
                        $store.state.meals.search_query &&
                        !searched_classifications.length
                    "
                    class="mt-6 text-gray-500"
                >
                    Showing meals matching "{{
                        $store.state.meals.search_query
                    }}"
                </p>
            </div>

            <div class="flex space-x-4">
                <button
                    v-show="isFiltered"
                    @click="clearSearch"
                    class="muted-text-btn"
                >
                    Reset
                </button>
                <button
                    @click="showOptions = true"
                    class="text-gray-500 shadow flex space-x-2 items-center px-3 py-2 rounded-full hover:bg-indigo-50"
                >
                    <SearchIcon class="text-gray-400 w-4 h-4" />
                    <span class="">Search</span>
                </button>
            </div>
        </div>

        <Modal :show="showOptions" @close="showOptions = false">
            <form
                @submit.prevent="doSearch"
                class="w-screen max-w-xl mx-auto p-6 rounded-lg bg-white"
            >
                <p class="font-bold text-xl">Search all meals</p>
                <InputField label="Matching this text" v-model="term" />

                <p class="text-sm font-bold mt-6 mb-4">
                    And having these classifications:
                </p>
                <div class="flex flex-wrap">
                    <div
                        v-for="classification in classifications"
                        :key="classification.id"
                        class="px-2 py-1 shadow rounded-md text-sm mb-3 mr-3"
                    >
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                class="text-indigo-500 focus:outline-none focus:ring-0"
                                :value="classification.id"
                                v-model="selected_classifications"
                            />
                            <span>{{ classification.name }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-4 items-center">
                    <button
                        type="button"
                        class="muted-text-btn"
                        @click="showOptions = false"
                    >
                        Cancel
                    </button>
                    <button class="btn btn-main">Search</button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<script setup>
import { useStore } from "vuex";
import { ref, computed } from "vue";
import Modal from "../Modal.vue";
import TextField from "../Forms/TextField.vue";
import InputField from "../Forms/InputField.vue";
import SearchIcon from "../Icons/SearchIcon.vue";

const store = useStore();

const term = ref("");

const showOptions = ref(false);

const classifications = computed(() => store.state.meals.classifications);
const selected_classifications = ref([]);
const searched_classifications = computed(
    () => store.getters["meals/searchedClassifications"]
);
const isFiltered = computed(() => store.getters["meals/isFiltered"]);

const doSearch = () => {
    store.dispatch("meals/searchMeals", {
        search: term.value,
        classifications: selected_classifications.value,
    });

    term.value = "";
    selected_classifications.value = [];
    showOptions.value = false;
};

const clearSearch = () => {
    term.value = "";
    selected_classifications.value = [];
    showOptions.value = false;
    store.dispatch("meals/searchMeals", {
        search: term.value,
        classifications: selected_classifications.value,
    });
};
</script>
