<template>
    <page>
        <div
            class="mt-48 py-24 px-12 bg-yellow-400 flex justify-center items-center relative shadow-lg"
        >
            <div
                class="absolute inset-0 transform rotate-3 bg-red-400"
                style="z-index: -1;"
            ></div>
            <p class="text-6xl text-gray-700 font-black">
                {{ quote.message }}
            </p>
            <p class="absolute bottom-0 right-0 m-4 font-semibold">
                - {{ quote.author }}
            </p>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../Components/UI/Page";
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    components: { Page },

    setup() {
        const store = useStore();

        const quote = computed(() => store.getters["quotes/random"]);

        return { quote };
    },
};
</script>
