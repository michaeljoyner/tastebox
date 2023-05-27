<template>
    <div>
        <div class="bg-indigo-700">
            <router-link to="/">
                <p class="type-h1 px-6 pt-6 pb-3">TasteBox</p>
            </router-link>

            <div class="mt-4 px-6 py-3 bg-indigo-600 flex justify-between">
                <p class="font-light">{{ username }}</p>
                <user-drop-down @logout="logout"></user-drop-down>
            </div>
        </div>

        <div class="p-6">
            <div class="my-6">
                <p class="type-h3 mb-2">Members</p>
                <div class="pl-2">
                    <router-link
                        class="font-bold hover:underline mb-1 block"
                        to="/memberships/members"
                        >All members
                    </router-link>
                </div>
            </div>

            <div class="my-6">
                <p class="type-h3 mb-2">Marketing</p>
                <div class="pl-2">
                    <router-link
                        class="font-bold hover:underline mb-1 block"
                        to="/blog"
                        >Blog
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block"
                        to="/discount-codes"
                        >Discounts
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block"
                        to="/instagram"
                        >Instagram
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block"
                        to="/mailing-list"
                        >Mailing List
                    </router-link>
                </div>
            </div>

            <div class="my-6">
                <p class="type-h3 mb-2">Reports</p>
                <div class="pl-2">
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/reports/meal-popularity"
                        >Meal Stats
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/reports/weekly-batch-report"
                        >Week by Week
                    </router-link>
                </div>
            </div>

            <div class="my-6">
                <p class="type-h3 mb-2">Food</p>
                <div class="pl-2">
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/meals"
                        >Meals
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/menus"
                        >Menus
                    </router-link>
                </div>
            </div>

            <div class="my-6">
                <p class="type-h3 mb-2">Orders</p>
                <div class="pl-2">
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/current-batch/"
                        >This Week
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/orders"
                        >Orders
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/orders/upcoming-kits"
                        >Upcoming Kits
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/ordered-kits"
                        >Ordered Kits
                    </router-link>
                    <router-link
                        class="font-bold hover:underline mb-1 block whitespace-nowrap"
                        to="/adjustments"
                        >Adjustments
                    </router-link>
                </div>
            </div>
        </div>
        <form action="/logout" method="post" ref="logoutForm">
            <input type="hidden" name="_token" :value="csrf_token" />
        </form>
    </div>
</template>

<script type="text/babel">
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import UserDropDown from "./Components/UI/UserDropDown.vue";

export default {
    components: { UserDropDown },
    setup() {
        const store = useStore();

        const username = computed(() => store.state.me.username);

        const csrf_token = ref(null);
        const logoutForm = ref(null);

        onMounted(() => {
            csrf_token.value =
                document.querySelector("#csrf-token-meta").content;
        });

        const logout = () => {
            logoutForm.value.submit();
        };

        return { username, csrf_token, logoutForm, logout };
    },
};
</script>
