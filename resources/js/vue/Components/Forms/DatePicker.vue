<template>
    <label>
        <span class="form-label">{{ label }}</span>
        <input type="date" v-model="inputValue" />
    </label>
</template>

<script type="text/babel">
import { useModelWrapper } from "../../../libs/useModelWrapper";

export default {
    props: ["modelValue", "label", "help-text", "error-msg"],
    emits: ["update:modelValue"],

    setup(props, { emit }) {
        return {
            inputValue: useModelWrapper(props, emit),
        };
    },
};
</script>
