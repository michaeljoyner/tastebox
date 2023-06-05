<template>
    <page>
        <page-header title="Tastebox Members"></page-header>

        <div class="mt-12 flex justify-end space-x-3 items-center">
            <button
                class="text-sm font-semibold hover:text-blue-500"
                :disabled="current_page === 1"
                @click="prevPage"
            >
                &larr; Prev Page
            </button>
            <p class="text-sm">Page {{ current_page }} of {{ total_pages }}</p>

            <button
                class="text-sm font-semibold hover:text-blue-500"
                :disabled="current_page >= total_pages"
                @click="nextPage"
            >
                Next Page &rarr;
            </button>
        </div>

        <div class="my-6">
            <table class="w-full">
                <thead class="bg-slate-200">
                    <tr class="text-left text-sm">
                        <th class="p-2">Name</th>
                        <th class="p-2">Location</th>
                        <th class="p-2 text-right">Last Order</th>
                    </tr>
                </thead>
                <tbody v-show="!fetching" class="">
                    <tr
                        v-for="(member, index) in members"
                        :key="member.id"
                        class="text-gray-600 text-sm border-b border-white"
                        :class="{ 'bg-slate-50': index % 2 }"
                    >
                        <td class="px-2 py-1">
                            <div class="flex items-center space-x-2">
                                <router-link
                                    class="muted-text-btn"
                                    :to="`/memberships/members/${member.id}/show`"
                                    >{{ member.full_name }}</router-link
                                >
                                <ShieldIcon
                                    class="w-4 h-4 text-green-500"
                                    v-show="member.verified"
                                />
                                <CheckIcon
                                    class="w-3 h-3 text-blue-500"
                                    v-if="member.profile_complete"
                                />
                            </div>
                        </td>
                        <td class="px-2 py-1">{{ member.location }}</td>
                        <td class="px-2 py-1 text-right">
                            {{ latestOrder(member.orders) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div
                class="flex justify-center items-center text-blue-500 mt-6"
                v-show="fetching"
            >
                <SpinningIcon class="text-indigo-500 w-6 h-6" />
            </div>
        </div>
    </page>
</template>

<script setup>
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import { showSuccess } from "../../../libs/notifications.js";
import ShieldIcon from "../../Components/Icons/ShieldIcon.vue";
import CheckIcon from "../../Components/Icons/CheckIcon.vue";
import SpinningIcon from "../../Components/Icons/SpinningIcon.vue";

const store = useStore();

const members = computed(() => store.state.members.list);
const current_page = computed(() => store.state.members.current_page);
const total_pages = computed(() => store.state.members.total_pages);
const fetching = ref(true);

const nextPage = () => {
    if (current_page.value >= total_pages.value) {
        return showSuccess("This is the last page");
    }
    fetchPage(current_page.value + 1);
};

const prevPage = () => {
    if (current_page.value <= 1) {
        return showSuccess("This is the first page");
    }
    fetchPage(current_page.value - 1);
};

const fetchPage = (page) => {
    fetching.value = true;
    store.dispatch("members/fetch", page).then(() => (fetching.value = false));
};

onMounted(() => {
    store.dispatch("members/fetch").then(() => (fetching.value = false));
});

const latestOrder = (orders) => {
    if (!orders || !orders.length) {
        return "-";
    }

    const latest = orders
        .filter((o) => o.is_paid)
        .sort((a, b) => {
            a.created_ts - b.created_ts;
        })
        .at(0);

    return latest ? latest.date : "-";
};
</script>
