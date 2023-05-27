<template>
    <div class="relative">
        <Menu>
            <MenuButton>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 fill-current"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                    />
                </svg>
            </MenuButton>
            <MenuItems
                class="fixed left-8 p-4 rounded-lg bg-white shadow-xl flex flex-col w-48 items-end"
            >
                <MenuItem v-slot="{ active }">
                    <button
                        @click="$emit('logout')"
                        :class="{
                            'text-indigo-500': active,
                            'text-gray-800': !active,
                        }"
                    >
                        Logout
                    </button>
                </MenuItem>
            </MenuItems>
        </Menu>
    </div>
</template>

<script type="text/babel">
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

export default {
    components: {
        Menu,
        MenuButton,
        MenuItems,
        MenuItem,
    },

    emits: ["logout"],
};
</script>
