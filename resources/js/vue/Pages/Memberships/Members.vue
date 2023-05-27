<template>
    <page>
        <page-header title="Tastebox Members"></page-header>

        <div class="my-12 flex justify-end space-x-8 items-center">
            <p>Showing page {{ current_page }} of {{ total_pages }}</p>
            <button
                class="text-sm font-semibold hover:text-blue-500"
                :disabled="current_page === 1"
                @click="prevPage"
            >
                &larr; Prev Page
            </button>
            <button
                class="text-sm font-semibold hover:text-blue-500"
                :disabled="current_page >= total_pages"
                @click="nextPage"
            >
                Next Page &rarr;
            </button>
        </div>

        <div class="my-12">
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Name</th>
                        <th class="p-2">Location</th>
                        <th class="p-2">Signed up</th>
                        <th class="p-2">Verified</th>
                        <th class="p-2">Profile Info</th>
                    </tr>
                </thead>
                <tbody v-show="!fetching">
                    <tr
                        v-for="(member, index) in members"
                        :key="member.id"
                        class="text-gray-600 text-sm"
                        :class="{ 'bg-blue-50': index % 2 }"
                    >
                        <td class="px-2 py-1">
                            <router-link
                                class="muted-text-btn"
                                :to="`/memberships/members/${member.id}/show`"
                                >{{ member.full_name }}</router-link
                            >
                        </td>
                        <td class="px-2 py-1">{{ member.location }}</td>
                        <td class="px-2 py-1">{{ member.signed_up }}</td>
                        <td>
                            <div
                                class="w-2 h-2 mx-auto rounded-full bg-green-500"
                                v-show="member.verified"
                            ></div>
                        </td>
                        <td>
                            <div
                                class="w-2 h-2 mx-auto rounded-full bg-green-500"
                                v-show="member.profile_complete"
                            ></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div
                class="flex justify-center items-center text-blue-500 mt-6"
                v-show="fetching"
            >
                <svg
                    class="h-8 animate-spin fill-current"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M17.584 9.372h2a9.554 9.554 0 0 0-.668-2.984L17.16 7.402c.224.623.371 1.283.424 1.97zm-3.483-8.077a9.492 9.492 0 0 0-3.086-.87v2.021a7.548 7.548 0 0 1 2.084.585l1.002-1.736zm2.141 4.327l1.741-1.005a9.643 9.643 0 0 0-2.172-2.285l-1.006 1.742a7.625 7.625 0 0 1 1.437 1.548zm-6.228 11.949a7.6 7.6 0 0 1-7.6-7.6c0-3.858 2.877-7.036 6.601-7.526V.424C4.182.924.414 5.007.414 9.971a9.6 9.6 0 0 0 9.601 9.601c4.824 0 8.807-3.563 9.486-8.2H17.48c-.658 3.527-3.748 6.199-7.466 6.199z"
                    />
                </svg>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import { showSuccess } from "../../../libs/notifications.js";
export default {
    components: { PageHeader, Page },

    setup() {
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
            store
                .dispatch("members/fetch", page)
                .then(() => (fetching.value = false));
        };

        onMounted(() => {
            store
                .dispatch("members/fetch")
                .then(() => (fetching.value = false));
        });

        return {
            members,
            current_page,
            total_pages,
            nextPage,
            prevPage,
            fetching,
        };
    },
};
</script>
