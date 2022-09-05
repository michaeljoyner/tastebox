<template>
    <div class="pb-6">
        <div v-if="!registered" class="bg-green-700 p-4 md:p-6 text-white">
            <p class="text-left md:text-center">
                Already a member? Don't forget to
                <a
                    href="/login"
                    class="font-bold underline hover:text-green-300"
                    >sign in</a
                >
                to get the most out of your account.
            </p>
        </div>
        <div v-if="showMenus" class="py-6">
            <p class="type-h1 text-center">Choose a Menu</p>
            <p class="my-6 max-w-xl text-gray-600 mx-auto px-6">
                Each week has its own unique menu from which you can choose the
                meals you like. You can plan for the weeks ahead by ordering
                from multiple menus, or you may order more than one box from the
                same menu to help family and friends.
            </p>
            <div class="flex flex-col items-center px-6">
                <div
                    v-for="menu in menus"
                    :key="menu.id"
                    class="p-4 shadow-lg my-8 max-w-lg mx-6 w-full bg-white rounded-lg"
                >
                    <div class="">
                        <div>
                            <div class="flex justify-between items-center mb-8">
                                <p class="type-h2">
                                    Menu #{{ menu.week_number }}
                                </p>
                                <button
                                    v-if="!menuKits(menu).length"
                                    class="green-btn"
                                    @click="addKit(menu.id)"
                                >
                                    Build box
                                </button>
                                <button
                                    v-else
                                    @click="selectKit(menuKits(menu)[0].id)"
                                    class="green-btn"
                                >
                                    Continue
                                </button>
                            </div>

                            <div
                                class="flex flex-wrap w-full overflow-auto my-4"
                            >
                                <div
                                    v-for="meal in menu.meals"
                                    :key="meal.id"
                                    class="w-10 h-10 mr-4 mb-4 rounded-full overflow-hidden"
                                >
                                    <img
                                        :src="meal.thumb_img"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                            <p class="text-gray-600">
                                Delivered on
                                <span class="font-bold">{{
                                    menu.delivery_from_pretty
                                }}</span>
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0"></div>
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
                        <div class="mt-3">
                            <p class="text-gray-600">or</p>

                            <button class="type-b4" @click="addKit(menu.id)">
                                <span class="text-green-600 underline"
                                    >Build another box </span
                                >for this menu
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
import { eventHub } from "../../../libs/eventHub";
import HandIcon from "../Icons/HandIcon";

export default {
    components: {
        HandIcon,
        KitBuilder,
    },

    props: ["menus", "initial-basket", "initial-kit", "registered"],

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

        window.addEventListener("popstate", ({ state }) => {
            if (!state) {
                this.selected_kit_id = null;
            }
        });
    },

    methods: {
        addKit(menu_id) {
            axios
                .post("/my-kits", { menu_id })
                .then(({ data }) => {
                    this.kits.push(data);
                    this.selectKit(data.id);
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

        selectKit(kit_id) {
            window.history.pushState(
                { kit_id: kit_id },
                "",
                `/build-a-box?kit=${kit_id}`
            );
            this.selected_kit_id = kit_id;
            window.scrollTo(0, 0);
        },
    },
};
</script>
