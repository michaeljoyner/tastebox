<template>
    <div class="py-6">
        <div v-if="showMenus">
            <p class="text-5xl font-bold text-center">Choose a menu.</p>
            <p class="my-6 max-w-xl text-gray-600 mx-auto px-6">
                Each week has its own menu. You may plan ahead by ordering from
                multiple menus, or you may order more than one box from the same
                menu.
            </p>
            <div class="flex flex-col items-center px-6">
                <div
                    v-for="menu in menus"
                    :key="menu.id"
                    class="p-4 shadow my-8 max-w-lg mx-6 w-full"
                >
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center"
                    >
                        <div>
                            <p class="text-lg font-bold">
                                Menu #{{ menu.week_number }}
                            </p>
                            <p class="text-gray-600">
                                Delivered on
                                <span class="font-bold">{{
                                    menu.delivery_from_pretty
                                }}</span>
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <button
                                class="px-4 py-2 rounded-lg font-bold text-white bg-green-600 shadow hover:bg-green-400"
                                @click="addKit(menu.id)"
                            >
                                Build box
                            </button>
                        </div>
                    </div>
                    <div class="mt-4" v-show="menuKits(menu).length">
                        <p class="text-gray-600 uppercase text-sm mb-2">
                            In your basket:
                        </p>
                        <div
                            v-for="kit in menuKits(menu)"
                            :key="kit.id"
                            class="flex"
                        >
                            <p>{{ kit.name }}</p>
                            <button
                                @click="selected_kit_id = kit.id"
                                class="font-bold text-green-600 hover:text-green-500 ml-8"
                            >
                                Continue
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <kit-builder
            @kit-updated="updateKit"
            :menu="current_menu"
            :kit="current_kit"
            @done="selected_kit_id = null"
        ></kit-builder>
    </div>
</template>

<script type="text/babel">
import KitBuilder from "./KitBuilder";
export default {
    components: {
        KitBuilder,
    },

    props: ["menus", "initial-basket", "initial-kit"],

    data() {
        return {
            selected_kit_id: null,
            kits: [],
        };
    },

    computed: {
        current_kit() {
            return this.kits.find((kit) => kit.id === this.selected_kit_id);
        },

        current_menu() {
            if (!this.current_kit) {
                return null;
            }
            return this.menus.find(
                (menu) => menu.id === this.current_kit.menu_id
            );
        },

        showMenus() {
            return !this.selected_kit_id;
        },
    },

    mounted() {
        this.kits = this.initialBasket.kits;

        this.selected_kit_id = this.initialKit;
    },

    methods: {
        addKit(menu_id) {
            axios
                .post("/my-kits", { menu_id })
                .then(({ data }) => {
                    this.kits.push(data);
                    this.selected_kit_id = data.id;
                })
                .catch(console.log())
                .then(() => eventHub.$emit("basket-updated"));
        },

        updateKit(updated_kit) {
            console.log({ updated_kit });
            this.kits = this.kits.map((kit) => {
                if (kit.id === updated_kit.id) {
                    return updated_kit;
                }
                return kit;
            });
        },

        menuKits(menu) {
            return this.kits.filter((kit) => kit.menu_id === menu.id);
        },
    },
};
</script>
