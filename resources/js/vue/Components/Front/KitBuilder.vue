<template>
    <div class="py-12">
        <p class="text-5xl font-bold text-center">Choose a menu.</p>
        <div class="flex flex-col items-center">
            <div
                v-for="menu in menus"
                :key="menu.id"
                class="p-4 shadow my-8 max-w-lg mx-6 w-full"
            >
                <div class="flex justify-between items-center">
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
                    <div>
                        <button
                            class="px-4 py-2 rounded-lg font-bold text-white bg-green-600 shadow hover:bg-green-400"
                        >
                            Build kit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["menus"],
};
</script>
