<template>
    <Menu>
        <MenuButton
            class="focus:outline-none focus:ring-2 ring-green-500 rounded-full"
        >
            <div
                class="flex justify-center items-center w-8 h-8 rounded-full border border-green-600 bg-green-100 focus:outline-none"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 fill-current text-green-600"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>
        </MenuButton>
        <MenuItems
            class="bg-gray-50 shadow-lg flex flex-col items-start absolute top-16 right-6 p-6 rounded-lg"
        >
            <MenuItem
                disabled
                class="my-3 border-b border-gray-300 w-full pb-3"
            >
                <p class="text-xs uppercase text-right">
                    {{ user }}
                </p>
            </MenuItem>
            <MenuItem v-slot="{ active }" class="my-3 w-full text-right">
                <a
                    :class="{ 'text-green-600': active }"
                    href="/me/edit-profile"
                >
                    Account settings
                </a>
            </MenuItem>
            <MenuItem v-slot="{ active }" class="my-3 w-full text-right">
                <a
                    :class="{ 'text-green-600': active }"
                    href="/me/reset-password"
                >
                    Reset Password
                </a>
            </MenuItem>
            <MenuItem v-slot="{ active }" class="my-3 w-full text-right">
                <a :class="{ 'text-green-600': active }" href="/me/home">
                    Home
                </a>
            </MenuItem>
            <MenuItem v-slot="{ active }" class="my-3 w-full text-right">
                <button @click="logout" :class="{ 'text-green-600': active }">
                    Logout
                </button>
            </MenuItem>
        </MenuItems>
    </Menu>
</template>

<script type="text/babel">
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { onMounted, ref } from "vue";

export default {
    components: {
        Menu,
        MenuButton,
        MenuItems,
        MenuItem,
    },

    setup() {
        const logout = () => {
            document.querySelector("#logout-form").submit();
        };

        const user = window.appMember.name;

        return { logout, user };
    },
};
</script>
