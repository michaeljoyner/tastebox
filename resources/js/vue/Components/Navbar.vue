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
                <dropdown
                    class="text-white font-bold h-16 flex items-center px-6"
                    name="Marketing"
                >
                    <div slot="dropdown_content" class="py-3">
                        <router-link
                            class="text-gray-800 font-bold hover:underline mx-4 mb-2 block"
                            to="/discount-codes"
                            >Discounts</router-link
                        >
                        <router-link
                            class="text-gray-800 font-bold hover:underline mx-4 mb-2 block"
                            to="/instagram"
                            >Instagram</router-link
                        >
                        <router-link
                            class="text-gray-800 font-bold hover:underline mx-4 mb-2 block"
                            to="/mailing-list"
                            >Mailing List</router-link
                        >
                    </div>
                </dropdown>

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
                <dropdown
                    class="text-white font-bold h-16 bg-gray-700 flex items-center px-6"
                    :name="username"
                >
                    <div slot="dropdown_content" class="py-3">
                        <form action="/logout" method="post">
                            <input
                                type="hidden"
                                name="_token"
                                :value="csrf_token"
                            />
                            <button
                                class="text-gray-800 hover:text-blue-600"
                                type="submit"
                            >
                                Logout
                            </button>
                        </form>
                    </div>
                </dropdown>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import { Dropdown } from "@dymantic/vuetilities";

export default {
    components: {
        Dropdown,
    },

    computed: {
        username() {
            return this.$store.state.me.username;
        },

        csrf_token() {
            return document.querySelector("#csrf-token-meta").content;
        },
    },
};
</script>
