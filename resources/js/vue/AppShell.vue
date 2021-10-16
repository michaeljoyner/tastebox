<template>
    <div class="relative h-full lg:pl-64">
        <div
            class="fixed top-0 left-0 z-10 w-full lg:hidden bg-indigo-700 text-white w-full h-12 flex justify-between items-center px-6"
        >
            <p class="text-2xl font-black">TasteBox</p>
            <button @click="showNav = !showNav">
                <svg
                    v-show="!showNav"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 fill-current"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                <svg
                    v-show="showNav"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 fill-current"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
        </div>
        <div>
            <router-view></router-view>
        </div>
        <div
            class="bg-indigo-500 min-h-full fixed left-0 top-0 bottom-0 w-64 transform lg:transform-none transition overflow-y-auto text-white z-50"
            :class="{ 'translate-x-0': showNav, '-translate-x-64': !showNav }"
        >
            <main-nav></main-nav>
        </div>
    </div>
    <notification-hub></notification-hub>
</template>

<script type="text/babel">
import MainNav from "./MainNav";
import { ref, watch } from "vue";
import { useRoute } from "vue-router";
import NotificationHub from "./Components/NotificationHub";
export default {
    components: { NotificationHub, MainNav },

    setup() {
        const showNav = ref(false);
        const route = useRoute();

        watch(
            () => route.fullPath,
            () => (showNav.value = false)
        );

        return { showNav };
    },
};
</script>
