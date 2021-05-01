<template>
    <div class="h-16 w-full bg-gray-800 flex justify-between items-center">
        <div class="pl-6">
            <router-link
                to="/"
                class="text-white text-2xl font-bold hover:text-teal-500"
                >TasteBox</router-link
            >
        </div>
        <div class="flex justify-end items-center">
            <div class="flex items-center">
                <div
                    class="relative text-white font-bold h-16 flex items-center px-6 z-50"
                    @click.stop="showMarketingOptions = !showMarketingOptions"
                >
                    <span>Marketing</span>
                    <down-chevron class="h-5 ml-1"></down-chevron>
                    <div
                        class="py-3 absolute top-full left-0 bg-gray-800 text-white w-full text-right"
                        v-show="showMarketingOptions"
                        ref="marketingOptionsMenu"
                    >
                        <router-link
                            class="font-bold hover:underline mx-4 mb-2 block"
                            to="/blog"
                            >Blog</router-link
                        >
                        <router-link
                            class="font-bold hover:underline mx-4 mb-2 block"
                            to="/discount-codes"
                            >Discounts</router-link
                        >
                        <router-link
                            class="font-bold hover:underline mx-4 mb-2 block"
                            to="/instagram"
                            >Instagram</router-link
                        >
                        <router-link
                            class="font-bold hover:underline mx-4 mb-2 block"
                            to="/mailing-list"
                            >Mailing List</router-link
                        >
                    </div>
                </div>

                <router-link
                    class="text-white font-bold hover:underline mx-4"
                    to="/meals"
                    >Meals</router-link
                >
                <router-link
                    class="text-white font-bold hover:underline mx-4"
                    to="/menus"
                    >Menus</router-link
                >
                <router-link
                    class="text-white font-bold hover:underline mx-4"
                    to="/recent-orders"
                    >Orders</router-link
                >

                <router-link
                    class="text-white font-bold hover:underline mx-4"
                    to="/current-batch/"
                    >This Week</router-link
                >
            </div>
            <div>
                <div
                    class="relative text-white font-bold h-16 bg-gray-700 flex items-center px-6"
                    @click.stop="showUserOptions = !showUserOptions"
                >
                    <span>{{ username }}</span>
                    <down-chevron class="h-5 ml-1"></down-chevron>
                    <div
                        class="py-3 absolute top-full left-0 bg-gray-700 w-full text-right"
                        v-show="showUserOptions"
                        ref="userOptionsMenu"
                    >
                        <form action="/logout" method="post">
                            <input
                                type="hidden"
                                name="_token"
                                :value="csrf_token"
                            />
                            <button
                                class="text-white hover:underline font-semibold mx-4"
                                type="submit"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import { computed, ref, onMounted } from "vue";
import { useStore } from "vuex";
import DownChevron from "./UI/Icons/DownChevron";
export default {
    components: { DownChevron },
    setup() {
        const showUserOptions = ref(false);
        const showMarketingOptions = ref(false);
        const store = useStore();

        const userOptionsMenu = ref(null);
        const marketingOptionsMenu = ref(null);

        const username = computed(() => store.state.me.username);
        const csrf_token = document.querySelector("#csrf-token-meta").content;

        onMounted(() => {
            window.addEventListener("click", ({ target }) => {
                if (
                    !userOptionsMenu.value.contains(target) &&
                    !marketingOptionsMenu.value.contains(target)
                ) {
                    showMarketingOptions.value = false;
                    showUserOptions.value = false;
                }
            });
        });

        return {
            showUserOptions,
            showMarketingOptions,
            username,
            csrf_token,
            userOptionsMenu,
            marketingOptionsMenu,
        };
    },
};
</script>
