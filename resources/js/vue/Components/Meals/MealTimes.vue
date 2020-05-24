<template>
    <div>
        <span class="px-2 py-1 text-sm mr-6 rounded-lg bg-green-200"
            >Prep: {{ prep_time }}</span
        >
        <span class="px-2 py-1 text-sm mr-6 rounded-lg bg-orange-200"
            >Cook: {{ cook_time }}</span
        >
        <span class="px-2 py-1 text-sm mr-6 rounded-lg bg-indigo-200"
            >Total: {{ total_time }}</span
        >
    </div>
</template>

<script type="text/babel">
import { minutesForHumans } from "../../../libs/time_functions";

export default {
    props: ["meal"],

    computed: {
        prep_time() {
            return minutesForHumans(this.meal.prep_time);
        },

        cook_time() {
            return minutesForHumans(this.meal.cook_time);
        },

        total_time() {
            return minutesForHumans(this.meal.cook_time + this.meal.prep_time);
        },
    },
};
</script>
