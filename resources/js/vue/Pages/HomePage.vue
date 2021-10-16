<template>
    <page class="">
        <div
            class="bg-gradient-to-r from-indigo-500 to-pink-500 p-8 rounded-xl text-white"
        >
            <p class="text-3xl font-black">
                {{ quote.message }}
            </p>
            <p class="mt-6 text-right">- {{ quote.author }}</p>
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
