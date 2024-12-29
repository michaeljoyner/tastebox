<template>
    <div v-if="kit">
        <div v-show="view !== 'add-ons'">
            <h1 class="type-h1 font-bold text-center my-8">Build a Box</h1>

            <p class="max-w-xl mx-auto px-6 mb-6">
                Select the meals you want
                <span class="font-bold">for the week</span> below as well as the
                number of people you will be cooking for. There is a
                <span class="font-bold">minimum of 3 meals per box</span> and we
                suggest not over-ordering as the meals are designed to be
                <span class="font-bold">fresh for 5 days after delivery</span>.
            </p>

            <div class="px-6">
                <div
                    v-for="meal in menu.meals"
                    :key="meal.id"
                    class="w-5/5 max-w-3xl my-12 mx-auto shadow md:shadow-lg bg-white relative"
                >
                    <div
                        class="flex flex-col md:flex-row border-b border-gray-200"
                    >
                        <div class="w-full h-auto md:w-80">
                            <img
                                :src="meal.title_image"
                                :alt="meal.name"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="flex-1 p-3 pl-6">
                            <p class="type-h3">{{ meal.name }}</p>

                            <div class="flex flex-wrap my-2">
                                <p
                                    v-for="category in meal.classifications"
                                    :key="category.id"
                                    class="mr-3 border rounded border-black px-2 type-b3 mb-2 whitespace-nowrap"
                                >
                                    {{ category.name }}
                                </p>
                            </div>

                            <p class="mt-2 type-b3">{{ meal.description }}</p>

                            <div class="flex space-x-4 items-center mt-3">
                                <p
                                    class="font-serif font-bold text-sm px-2 py-1 rounded-md"
                                    :class="mealPriceClasses(meal.tier)"
                                >
                                    {{ meal.price }}
                                </p>
                                <p
                                    class="type-b4 my-2 flex items-center leading-none"
                                >
                                    <clock-icon
                                        class="text-green-400 h-4 mr-2"
                                    />
                                    <span class="type-b4"
                                        >{{ meal.cook_time }} mins</span
                                    >
                                </p>
                            </div>

                            <check-icon
                                v-if="mealIsInKit(meal.id)"
                                class="text-green-500 h-6 absolute top-0 right-0 mt-1 mr-1 bg-opaque rounded-full"
                            ></check-icon>
                        </div>
                    </div>
                    <div class="p-4">
                        <manage-servings
                            :meal-id="meal.id"
                            :kit-id="kit.id"
                            :current-state="kitMealServings(meal.id)"
                            @updated="setKit"
                            :can-add-to-box="kit.meals.length < 5"
                            @explain-limit="showLimitModal = true"
                        ></manage-servings>
                    </div>
                </div>
            </div>

            <div class="px-6 my-8 max-w-xl mx-auto">
                <p>
                    All done? We hope you’re excited to learn a few new recipes.
                    You can still go back to build another box, or proceed to
                    review your basket and checkout.
                </p>
                <div class="flex flex-col items-center mt-6">
                    <button
                        v-if="menu.add_ons.length"
                        class="px-4 py-1 font-serif rounded-md bg-green-600 text-green-100"
                        type="button"
                        @click="proceedToAddons"
                    >
                        Proceed
                    </button>

                    <a
                        v-if="!menu.add_ons.length"
                        class="px-4 py-1 font-serif rounded-md bg-green-600 text-green-100"
                        type="button"
                        href="/basket"
                    >
                        Go to Basket
                    </a>

                    <div class="mt-6 flex gap-8">
                        <button
                            class="text-sm text-gray-500 hover:text-green-500"
                            @click="$emit('done')"
                        >
                            Build another box
                        </button>

                        <a
                            v-if="menu.add_ons.length"
                            href="/basket"
                            class="text-sm text-gray-500 hover:text-green-500"
                            >Go to Basket</a
                        >
                    </div>
                </div>
            </div>
        </div>

        <div v-show="view === 'add-ons'">
            <h1 class="type-h1 font-bold text-center my-8">Before you go...</h1>

            <div class="px-6 mb-6">
                <p class="max-w-xl mx-auto">
                    You may add some extras to your kit, and we will deliver
                    them along with your meals.
                </p>

                <div class="flex justify-center items-center pt-4">
                    <a
                        href="/basket"
                        class="text-sm text-gray-500 hover:text-green-500"
                        >Skip to checkout</a
                    >
                </div>
            </div>

            <div class="max-w-3xl mx-auto px-6">
                <AddOnKitCard
                    v-for="addOn in menu.add_ons"
                    :key="addOn.uuid"
                    :add-on="addOn"
                    :kit-id="kit.id"
                    :initial-qty="
                        kit.add_ons.find((a) => a.id === addOn.id)?.qty || 0
                    "
                    @updated="setKit"
                />
            </div>

            <div class="flex flex-col items-center mt-10">
                <a
                    href="/basket"
                    class="px-4 py-1 font-serif text-green-100 bg-green-600 rounded-md"
                    >Go to Basket</a
                >
                <div class="flex mt-6">
                    <button
                        type="button"
                        @click="returnToMeals"
                        class="text-sm text-gray-500 hover:text-green-500"
                    >
                        Back to meals
                    </button>
                </div>
            </div>
        </div>
        <modal :show="showLimitModal" @close="showLimitModal = false">
            <div class="p-6 w-full max-w-md mx-auto bg-white">
                <p class="type-h3 text-green-700 mb-6">Your box is full!</p>
                <p class="type-b3">
                    A box may only contain up to 5 meals. If you really need
                    more, you could separate your order over two boxes. The cost
                    would be the same.
                </p>
                <p class="type-b3 mt-4">
                    Please bear in mind that our meals are only designed to stay
                    fresh until the Friday after delivery.
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        @click="showLimitModal = false"
                        class=""
                    >
                        Okay
                    </button>
                </div>
            </div>
        </modal>
    </div>
</template>

<script setup>
import ManageServings from "./ManageServings.vue";
import CheckIcon from "../UI/Icons/CheckIcon.vue";
import Modal from "../Modal.vue";
import { eventHub } from "../../../libs/eventHub.js";
import ClockIcon from "../Icons/ClockIcon.vue";
import { ref, onMounted } from "vue";
import AddOnKitCard from "./AddOnKitCard.vue";

const props = defineProps({ menu: Object, kit: Object, view: String });
const emit = defineEmits(["kit-updated", "show-add-ons"]);

const selectAddOns = ref(false);

const showLimitModal = ref(false);

const kitMealServings = (meal_id) => {
    const kit_meal = props.kit.meals.find((m) => m.id === meal_id);
    return kit_meal ? kit_meal.servings : 0;
};

const setKit = (updated_kit) => {
    eventHub.$emit("basket-updated");
    emit("kit-updated", updated_kit);
};

const mealIsInKit = (meal_id) => props.kit.meals.some((m) => m.id === meal_id);

const mealPriceClasses = (tier) => {
    const lookup = {
        Basic: "bg-orange-200 text-black",
        Standard: "bg-emerald-400 text-white",
        Premium: "bg-black text-white",
    };
    return lookup[tier] || lookup.Standard;
};

const proceedToAddons = () => {
    window.history.pushState(
        { kit_id: props.kit.id, from_addons: true },
        "",
        `/build-a-box?kit=${props.kit.id}&view=add-ons`
    );
    window.scrollTo(0, 0);
    emit("show-add-ons", true);
};

const returnToMeals = () => {
    window.history.pushState(
        { kit_id: props.kit.id, from_addons: false },
        "",
        `/build-a-box?kit=${props.kit.id}&view=meals`
    );
    emit("show-add-ons", false);
};
</script>
