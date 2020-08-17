<template>
    <page v-if="batch">
        <page-header :title="batch.name"> </page-header>

        <div class="flex">
            <div class="w-64">
                <div class="mb-3 pl-2">
                    <router-link
                        class="font-bold text-gray-700 hover:text-teal-600"
                        :to="`/current-batch/`"
                        >Overview</router-link
                    >
                </div>
                <div class="mb-3 pl-2">
                    <router-link
                        class="font-bold text-gray-700 hover:text-teal-600"
                        :to="`/current-batch/kits`"
                        >Ordered Kits</router-link
                    >
                </div>
                <div class="mb-3 pl-2">
                    <router-link
                        class="font-bold text-gray-700 hover:text-teal-600"
                        :to="`/current-batch/meals`"
                        >Meal List</router-link
                    >
                </div>
                <div class="mb-3 pl-2">
                    <router-link
                        class="font-bold text-gray-700 hover:text-teal-600"
                        :to="`/current-batch/ingredients`"
                        >Ingredients</router-link
                    >
                </div>
            </div>
            <div class="flex-1">
                <router-view></router-view>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        Page,
        PageHeader,
    },

    computed: {
        batch() {
            return this.$store.state.menus.current_batch;
        },
    },

    mounted() {
        this.$store
            .dispatch("menus/fetchCurrentBatch")
            .catch(() => showError("Unable to fetch current batch"));
    },
};
</script>
