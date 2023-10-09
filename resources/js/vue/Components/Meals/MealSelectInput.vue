<template>
    <div class="relative flex items-center space-x-4 mb-4">
        <div class="w-[20rem] flex-shrink-0">
            <Combobox v-model="selectedMeal">
                <ComboboxInput
                    class="w-full"
                    @change="search = $event.target.value"
                    :display-value="(meal) => meal.name"
                />
                <ComboboxOptions
                    class="absolute top-full max-h-[12rem] overflow-y-auto w-full shadow text-sm bg-white z-50"
                >
                    <ComboboxOption
                        v-for="meal in matchingMeals"
                        :key="meal.id"
                        :value="meal"
                        v-slot="{ active, selected }"
                        as="template"
                    >
                        <li class="py-1 px-2" :class="{ 'bg-blue-50': active }">
                            {{ meal.name }}
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </Combobox>
        </div>
        <p>x</p>
        <div>
            <input
                @input="emitUpdate"
                class="w-16"
                type="number"
                v-model="qty"
            />
        </div>
        <div>
            <button @click="$emit('delete', modelValue.key)">
                <TrashIcon class="w-4 h-4 text-gray-400 hover:text-pink-500" />
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from "@headlessui/vue";
import TrashIcon from "../Icons/TrashIcon.vue";

const props = defineProps({ mealList: Array, modelValue: Object });
const emit = defineEmits(["update:modelValue", "delete"]);

const search = ref("");
const selectedMeal = ref({ id: null, name: "" });
const matchingMeals = computed(() => {
    if (search.value.length < 4) {
        return [];
    }
    return props.mealList.filter((m) =>
        m.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

watch(
    () => selectedMeal.value,
    (meal) => {
        emitUpdate();
    }
);

const emitUpdate = () =>
    emit("update:modelValue", {
        meal: selectedMeal.value?.id,
        qty: qty.value,
        key: props.modelValue.key,
    });

const qty = ref(1);
</script>
