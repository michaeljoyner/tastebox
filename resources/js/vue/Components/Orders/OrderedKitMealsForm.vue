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

                    <colour-label
                        :colour="adjustmentValue > 0 ? 'green' : 'red'"
                        :text="`R${adjustmentValue}`"
                    ></colour-label>
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
                    <waiting-button :waiting="updating" @click="update"
                        >Update Kit</waiting-button
                    >
                </div>
            </div>
        </modal>
    </div>
</template>

<script type="text/babel">
import { computed, ref, watchEffect } from "vue";
import UpDowner from "../Forms/UpDowner";
import Modal from "../Modal";
import TextAreaField from "../Forms/TextAreaField";
import ColourLabel from "../UI/ColourLabel";
import { useStore } from "vuex";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import WaitingButton from "../UI/WaitingButton";

export default {
    components: { WaitingButton, ColourLabel, TextAreaField, Modal, UpDowner },
    props: ["kit"],
    emits: ["updated"],

    setup(props, { emit }) {
        const store = useStore();
        const original_meals = ref(props.kit.meals.map((m) => m));

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

        const form_meals = ref(getFormMeals(props.kit));

        watchEffect(() => {
            console.log(props.kit);
            original_meals.value = props.kit.meals.map((m) => m);
            form_meals.value = getFormMeals(props.kit);
        });

        const updated_meals = computed(() => {
            return form_meals.value.filter((m) => m.selected && m.servings);
        });
        const reason = ref("");

        const showSummary = ref(false);

        const [updating, update] = httpAction(
            () =>
                store.dispatch("kits/updateMeals", {
                    kit_id: props.kit.id,
                    formData: {
                        meals: updated_meals.value,
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

        const sumServings = (meals) =>
            meals.reduce((carry, meal) => carry + meal.servings, 0);

        const adjustmentValue = computed(() => {
            return (
                (sumServings(updated_meals.value) -
                    sumServings(original_meals.value)) *
                85
            );
        });

        return {
            original_meals,
            form_meals,
            showSummary,
            updated_meals,
            reason,
            update,
            updating,
            adjustmentValue,
        };
    },
};
</script>
