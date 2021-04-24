<template>
    <div class="" :class="{ 'border-b border-red-400': errorMsg }">
        <label class="flex items-center">
            <span class="text-sm">{{ label }}</span>
            <span class="text-xs text-red-400" v-show="errorMsg">{{
                errorMsg
            }}</span>
            <input
                ref="radio"
                type="radio"
                v-model="inputValue"
                class="hidden"
                :checked="inputValue === value"
                :value="value"
            />
            <div
                class="ml-3 w-4 h-4 rounded-full border border-black fake-radio"
            ></div>
            <p class="my-1 text-gray-500 text-sm" v-show="helpText">
                {{ helpText }}
            </p>
        </label>
    </div>
</template>

<script type="text/babel">
import { useModelWrapper } from "../../../libs/useModelWrapper";

export default {
    props: ["modelValue", "error-msg", "label", "help-text", "value"],
    emits: ["update:modelValue"],

    setup(props, { emit }) {
        return {
            inputValue: useModelWrapper(props, emit),
        };
    },
};
</script>

<style scoped>
input[type="radio"]:checked + .fake-radio {
    @apply bg-gray-700;
}
</style>
