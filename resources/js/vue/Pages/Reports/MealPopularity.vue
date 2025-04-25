<template>
    <page>
        <page-header title="Meal Popularity">
            <div
                class="flex space-x-2 px-4 py-2 border focus-within:ring-1 rounded-full"
            >
                <SearchIcon class="w-4 text-gray-400" />
                <input
                    v-model="query"
                    placeholder="Search by name"
                    class="border-0 focus:outline-none focus:ring-0"
                />
            </div>
        </page-header>

        <div class="my-12">
            <table class="w-full bg-slate-50">
                <thead>
                    <tr class="bg-slate-700">
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Meal
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Times Available
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Total Kits
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Total Servings
                        </th>
                        <th class="p-2 text-white text-left text-xs uppercase">
                            Last Available
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr
                        v-for="meal in meals"
                        :key="meal.id"
                        class="border-b border-white hover:bg-blue-50"
                    >
                        <td class="p-2 text-sm">
                            <router-link
                                :to="`/meals/${meal.id}/manage/info`"
                                class="hover:text-indigo-500"
                                >{{ meal.name }} -
                                <span class="text-xs">{{
                                    meal.popularity
                                }}</span>
                            </router-link>
                        </td>
                        <td class="p-2 text-sm font-bold">
                            {{ meal.times_offered }}
                        </td>
                        <td class="p-2 text-sm font-bold">
                            {{ meal.total_ordered }}
                        </td>
                        <td class="p-2 text-sm font-bold">
                            {{ meal.total_servings }}
                        </td>
                        <td class="p-2 text-xs whitespace-nowrap">
                            {{ meal.last_offered_ago }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import InputField from "../../Components/Forms/InputField.vue";
import SearchIcon from "../../Components/Icons/SearchIcon.vue";

const store = useStore();

const query = ref("");

const meals = computed(() => {
    return store.getters["meals/byPopularity"].filter((m) => {
        if (query.length < 3) {
            return true;
        }
        return m.name.toLowerCase().includes(query.value.toLowerCase());
    });
});

onMounted(() => {
    store.dispatch("meals/fetchAllUsed");
});
</script>
