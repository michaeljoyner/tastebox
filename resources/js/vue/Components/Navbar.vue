<template>
    <div class="h-16 w-full bg-gray-800 flex justify-between items-center">
        <div class="pl-6">
            <router-link
                to="/"
                class="text-white text-2xl font-bold hover:text-teal-500"
                >Tastebox</router-link
            >
        </div>
        <div class="flex justify-end items-center">
            <div>
                <router-link
                    class="text-white font-bold hover:underline mx-4"
                    to="/meals"
                    >Meals</router-link
                >
                <router-link
                    class="text-white font-bold hover:underline mx-4"
                    to="/baskets"
                    >Baskets</router-link
                >
            </div>
            <div>
                <dropdown
                    class="text-white font-bold h-16 bg-gray-700 flex items-center px-6"
                    :name="username"
                >
                    <div slot="dropdown_content" class="py-3">
                        <a href="/" class="text-gray-800">A sewwt link</a>
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
    },
};
</script>
