<template>
    <div>
        <div class="max-w-2xl border border-gray-200">
            <div
                v-for="meal in form_meals"
                :key="meal.id"
                class="flex space-x-3 items-center border-b border-gray-200 p-3"
            >
                <button
                    class="block w-8 h-8 rounded-full"
                    :class="{
                        'bg-pink-500': meal.selected,
                        'bg-gray-200': !meal.selected,
                    }"
                    @click="meal.selected = !meal.selected"
                ></button>
                <p class="flex-1 px-6">{{ meal.name }}</p>
                <up-downer
                    v-show="meal.selected"
                    v-model="meal.servings"
                    :options="[1, 2, 3, 4, 5, 6, 7, 8]"
                ></up-downer>
            </div>

            <hr class="my-6">

            <div
                v-for="addOn in form_addOns"
                :key="addOn.id"
                class="flex space-x-3 items-center border-b border-gray-200 p-3"
            >
                <button
                    class="block w-8 h-8 rounded-full"
                    :class="{
                        'bg-pink-500': addOn.selected,
                        'bg-gray-200': !addOn.selected,
                    }"
                    @click="addOn.selected = !addOn.selected"
                ></button>
                <p class="flex-1 px-6">{{ addOn.name }}</p>
                <up-downer
                    v-show="addOn.selected"
                    v-model="addOn.qty"
                    :options="[1, 2, 3, 4, 5, 6, 7, 8]"
                ></up-downer>
            </div>
        </div>
        <div class="my-6">
            <button class="btn" @click="showSummary = true">Update</button>
        </div>

        <modal :show="showSummary" @close="showSummary = false">
            <div class="max-w-3xl mx-auto w-full p-6 rounded-lg bg-white">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-bold text-indigo-600">
                        Update Summary
                    </p>


                </div>

                <div class="p-3 rounded-md bg-blue-500 my-4 text-white">
                    <p class="text-sm">Once the kit is updated, please visit the "Adjustments" page to see how much money TasteBox needs to either reimburse or reclaim.</p>
                </div>

                <div class="my-6 flex justify-between items-center">
                    <div class="w-64">
                        <p class="text-pink-500 font-bold">Original Kit</p>
                        <p
                            v-for="original in original_meals"
                            :key="original.id"
                            class="text-sm"
                        >
                            {{ original.servings }} x {{ original.name }}
                        </p>
                        <hr class="my-3">
                        <p class="text-sm" v-for="add_on in original_add_ons" :key="add_on.id">{{ add_on.name}} x {{add_on.qty}}</p>
                    </div>
                    <span class="text-5xl text-pink-500">&rarr;</span>
                    <div class="w-64">
                        <p class="text-pink-500 font-bold">Updated Kit</p>
                        <p
                            v-for="updated in updated_meals"
                            :key="updated.id"
                            class="text-sm"
                        >
                            {{ updated.servings }} x {{ updated.name }}
                        </p>
                        <hr class="my-3">
                        <p
                            v-for="updated in updated_add_ons"
                            :key="updated.id"
                            class="text-sm"
                        >
                            {{ updated.name }} x {{ updated.qty }}
                        </p>
                    </div>
                </div>

                <div class="my-6">
                    <text-area-field
                        label="Reason"
                        help-text="Include a reason for the adjustment"
                        v-model="reason"
                    ></text-area-field>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button class="" @click="showSummary = false">
                        Cancel
                    </button>
                    <SubmitButton :waiting="updating" @click="update"
                        >Update Kit</SubmitButton
                    >
                </div>
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">
import { computed, ref, watchEffect } from "vue";
import UpDowner from "../Forms/UpDowner.vue";
import Modal from "../Modal.vue";
import TextAreaField from "../Forms/TextAreaField.vue";
import ColourLabel from "../UI/ColourLabel.vue";
import { useStore } from "vuex";
import { httpAction } from "../../../libs/httpAction.js";
import { showError, showSuccess } from "../../../libs/notifications.js";
import WaitingButton from "../UI/WaitingButton.vue";
import SubmitButton from "../UI/SubmitButton.vue";

export default {
    components: {SubmitButton, WaitingButton, ColourLabel, TextAreaField, Modal, UpDowner },
    props: ["kit"],
    emits: ["updated"],

    setup(props, { emit }) {
        const store = useStore();
        const original_meals = ref(props.kit.meals.map((m) => m));
        const original_add_ons = ref(props.kit.add_ons.map((m) => m));

        const getFormMeals = (kit) => {
            return kit.available_meals.map((m) => ({
                id: m.id,
                name: m.name,
                selected: !!original_meals.value.find((o) => o.id === m.id),
                servings:
                    original_meals.value.find((o) => o.id === m.id)?.servings ||
                    0,
            }));
        };

        const getFormAddOns = kit => {
            return kit.available_add_ons.map(ad => ({
                id: ad.id,
                name: ad.name,
                selected: !!original_add_ons.value.find(a => a.id === ad.id),
                qty: original_add_ons.value.find(a => a.id === ad.id)?.qty || 0,
            }))
        }

        const form_meals = ref(getFormMeals(props.kit));
        const form_addOns = ref(getFormAddOns(props.kit));

        watchEffect(() => {
            original_meals.value = props.kit.meals.map((m) => m);
            form_meals.value = getFormMeals(props.kit);
            form_addOns.value = getFormAddOns(props.kit);
        });

        const updated_meals = computed(() => {
            return form_meals.value.filter((m) => m.selected && m.servings);
        });
        const updated_add_ons = computed(() => {
            return form_addOns.value.filter((m) => m.selected && m.qty);
        });
        const reason = ref("");

        const showSummary = ref(false);

        const [updating, update] = httpAction(
            () =>
                store.dispatch("kits/updateMeals", {
                    kit_id: props.kit.id,
                    formData: {
                        meals: updated_meals.value,
                        add_ons: updated_add_ons.value,
                        reason: reason.value,
                    },
                }),
            () => {
                showSuccess("Kit updated");
                showSummary.value = false;
                emit("updated");
            },
            () => {
                showError("Failed to update kit");
            }
        );



        return {
            original_meals,
            original_add_ons,
            form_meals,
            form_addOns,
            showSummary,
            updated_meals,
            updated_add_ons,
            reason,
            update,
            updating,
        };
    },
};
</script>
