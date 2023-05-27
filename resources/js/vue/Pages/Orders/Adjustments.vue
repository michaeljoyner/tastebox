<template>
    <page>
        <page-header title="Adjustments"></page-header>

        <div class="my-12 max-w-3xl flex justify-end items-center space-x-8">
            <button
                :disabled="current_page === 1"
                class="muted-text-btn"
                @click="prevPage"
            >
                &larr; Prev
            </button>

            <p>Page {{ current_page }} of {{ total_pages }}</p>

            <button
                :disabled="current_page >= total_pages"
                class="muted-text-btn"
                @click="nextPage"
            >
                Next &rarr;
            </button>
        </div>

        <div class="my-12 max-w-3xl">
            <div class="flex justify-center">
                <spinning-icon
                    class="w-8 h-8 text-pink-500"
                    v-show="fetching"
                ></spinning-icon>
            </div>
            <adjustments-table
                v-show="!fetching"
                :adjustments="adjustments"
            ></adjustments-table>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import CheckIcon from "../../Components/Icons/CheckIcon.vue";
import WarningIcon from "../../Components/Icons/WarningIcon.vue";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import { httpAction } from "../../../libs/httpAction.js";
import { showError } from "../../../libs/notifications.js";
import SpinningIcon from "../../Components/Icons/SpinningIcon.vue";
import AdjustmentsTable from "../../Components/Orders/AdjustmentsTable.vue";
export default {
    components: {
        AdjustmentsTable,
        SpinningIcon,
        ColourLabel,
        WarningIcon,
        CheckIcon,
        PageHeader,
        Page,
    },

    setup() {
        const store = useStore();

        const adjustments = computed(() => store.state.adjustments.list);
        const current_page = computed(() => store.state.adjustments.page);
        const total_pages = computed(() => store.state.adjustments.total_pages);

        onMounted(() => {
            store.dispatch("adjustments/refresh");
        });

        const nextPage = () => {
            store.commit("adjustments/nextPage");
            fetch();
        };

        const prevPage = () => {
            store.commit("adjustments/prevPage");
            fetch();
        };

        const [fetching, fetch] = httpAction(
            () => store.dispatch("adjustments/refresh"),
            () => {},
            () => showError("Failed to fetch page")
        );

        return {
            adjustments,
            current_page,
            total_pages,
            nextPage,
            prevPage,
            fetching,
        };
    },
};
</script>
