<template>
    <div class="" :class="{ 'border-b border-red-400': errorMsg }">
        <label class="">
            <span class="form-label">{{ label }}</span>
            <span class="text-xs text-red-400" v-show="errorMsg">{{
                errorMsg
            }}</span>
            <p class="my-1 text-gray-500 text-sm" v-show="helpText">
                {{ helpText }}
            </p>
            <textarea
                ref="input"
                v-model="inputValue"
                class="form-input"
                :class="heightClass"
            ></textarea>
        </label>
    </div>
</template>

<script type="text/babel">
import { useModelWrapper } from "../../../libs/useModelWrapper";

export default {
    props: [
        "modelValue",
        "error-msg",
        "input-name",
        "label",
        "type",
        "help-text",
        "height",
    ],
    emits: ["update:modelValue"],

    setup(props, { emit }) {
        const acceptedHeights = {
            small: "h-24",
            regular: "h-32",
            tall: "h-48",
        };

        const heightClass = acceptedHeights[props.height] || "h-32";

        return {
            inputValue: useModelWrapper(props, emit),
            heightClass,
        };
    },
};
</script>
