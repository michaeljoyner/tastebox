<template>
    <button
        @click="$emit('toggled')"
        class="relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:shadow-outline"
        :class="colour"
    >
        <span
            class="inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full"
            :class="translate"
        ></span>
    </button>
</template>

<script type="text/babel">
import { computed } from "vue";

export default {
    props: ["status", "busy"],

    emits: ["toggled"],

    setup(props) {
        const translate = computed(() => {
            if (props.busy) {
                return "translate-x-2";
            }
            return props.status ? "translate-x-5" : "translate-x-0";
        });
        const colour = computed(() => {
            if (props.busy) {
                return "bg-gray-200";
            }
            return props.status ? "bg-green-500" : "bg-gray-200";
        });

        return { colour, translate };
    },
};
</script>
