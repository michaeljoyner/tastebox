<template>
    <page>
        <page-header title="Ordered Kits"></page-header>

        <div class="mb-12 flex justify-end space-x-4">
            <p>Showing page {{ current_page }} of {{ total_pages }}</p>
            <button
                @click="prevPage"
                class="text-btn"
                :disabled="fetching || current_page <= 1"
            >
                &larr; Prev
            </button>

            <button
                @click="nextPage"
                class="text-btn"
                :disabled="fetching || current_page >= total_pages"
            >
                Next &rarr;
            </button>
        </div>

        <div class="my-12">
            <div v-show="fetching" class="flex justify-center items-center">
                <spinning-icon class="text-indigo-500 h-8 w-8"></spinning-icon>
            </div>

            <table class="w-full p-4 shadow">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Date (Delivery)</th>
                        <th class="p-2">Customer</th>
                        <th class="p-2">Meals/Servings</th>
                    </tr>
                </thead>
                <tbody v-show="!fetching">
                    <tr v-for="kit in kits" :key="kit.id">
                        <td class="px-2 py-1 text-sm">
                            <router-link
                                class="hover:text-indigo-700"
                                :to="`/ordered-kits/${kit.id}/show`"
                            >
                                {{ kit.delivery_date }}
                            </router-link>
                        </td>
                        <td class="px-2 py-1">
                            <router-link
                                class="hover:text-indigo-700"
                                :to="`/ordered-kits/${kit.id}/show`"
                            >
                                {{ kit.customer_name }}
                            </router-link>
                        </td>
                        <td class="px-2 py-1 text-sm">
                            {{ mealSummary(kit.meals) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import { httpAction } from "../../../libs/httpAction";
import SpinningIcon from "../../Components/Icons/SpinningIcon";
export default {
    components: { SpinningIcon, PageHeader, Page },

    setup() {
        const store = useStore();

        const kits = computed(() => store.state.kits.list);
        const current_page = computed(() => store.state.kits.current_page);
        const total_pages = computed(() => store.state.kits.total_pages);

        onMounted(() => {
            store.dispatch("kits/fetch");
        });

        const [fetching, refresh] = httpAction(
            () => store.dispatch("kits/refresh"),
            () => {},
            () => {}
        );

        const prevPage = () => {
            store.commit("kits/prevPage");
            refresh();
        };

        const nextPage = () => {
            store.commit("kits/nextPage");
            refresh();
        };

        const mealSummary = (meals) => {
            const number_meals = meals.length;
            const number_servings = meals.reduce(
                (carry, meal) => carry + meal.servings,
                0
            );

            return `${number_meals} meal (${number_servings} servings)`;
        };

        return {
            kits,
            current_page,
            total_pages,
            nextPage,
            prevPage,
            fetching,
            mealSummary,
        };
    },
};
</script>
