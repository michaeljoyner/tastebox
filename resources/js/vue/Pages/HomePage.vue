<template>
    <page class="">
        <div
            class="bg-gradient-to-r from-indigo-500 to-pink-500 p-8 rounded-xl text-white mt-12 md:mt-0"
        >
            <p class="text-3xl font-black">
                {{ quote.message }}
            </p>
            <p class="mt-6 text-right">- {{ quote.author }}</p>
        </div>

        <div class="my-12" v-if="batch">
            <p class="text-lg font-black">
                Next delivery: {{ batch.delivery_date }}
            </p>

            <div class="mt-6 flex flex-col md:flex-row space-x-6">
                <div
                    class="py-6 px-12 rounded-lg shadow-md flex flex-col items-center"
                >
                    <p class="text-6xl font-black mb-4 text-indigo-500">
                        {{ batch.total_kits }}
                    </p>
                    <p class="text-center font-semibold text-gray-500">Kits</p>
                </div>
                <div
                    class="py-6 px-12 rounded-lg shadow-md flex flex-col items-center"
                >
                    <p class="text-6xl font-black mb-4 text-indigo-500">
                        {{ batch.total_meals }}
                    </p>
                    <p class="text-center font-semibold text-gray-500">Meals</p>
                </div>
                <div
                    class="py-6 px-12 rounded-lg shadow-md flex flex-col items-center"
                >
                    <p class="text-6xl font-black mb-4 text-indigo-500">
                        {{ batch.total_servings }}
                    </p>
                    <p class="text-center font-semibold text-gray-500">
                        Servings
                    </p>
                </div>
            </div>
        </div>

        <div v-show="unresolved_adjustments.length > 0" class="my-12">
            <p class="text-lg font-black">You have unresolved adjustments.</p>
            <adjustments-table
                :adjustments="unresolved_adjustments"
            ></adjustments-table>
        </div>

        <div class="my-12">
            <p class="text-lg font-black">Recent Activity</p>

            <div>
                <div
                    v-for="activity in activities"
                    :key="activity.id"
                    class="flex items-center space-x-6 text-sm py-2"
                >
                    <p>{{ activity.created_at }}</p>
                    <div>
                        <router-link
                            v-show="activity.url"
                            :to="activity.url"
                            class="hover:text-blue-500"
                        >
                            {{ activity.activity }}
                        </router-link>
                        <p v-show="!activity.url">{{ activity.activity }}</p>
                    </div>
                </div>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../Components/UI/Page.vue";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import AdjustmentsTable from "../Components/Orders/AdjustmentsTable.vue";
import { showError } from "../../libs/notifications.js";
export default {
    components: { AdjustmentsTable, Page },

    setup() {
        const store = useStore();

        const quote = computed(() => store.getters["quotes/random"]);

        const unresolved_adjustments = computed(
            () => store.state.adjustments.unresolved
        );

        const activities = computed(() => store.getters["activityLogs/latest"]);

        const batch = computed(() => store.state.menus.current_batch);

        onMounted(() => {
            store.dispatch("adjustments/fetchUnresolved");
            store.dispatch("activityLogs/fetch");
            store
                .dispatch("menus/fetchCurrentBatch")
                .catch(() => showError("Failed to fetch current batch"));
        });

        return { quote, unresolved_adjustments, activities, batch };
    },
};
</script>
