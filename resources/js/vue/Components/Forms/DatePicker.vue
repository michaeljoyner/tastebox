<template>
    <label>
        <label class="form-label block">{{ label }}</label>
        <p v-if="errorMsg" class="text-sm text-red-500">{{ errorMsg }}</p>
        <input type="date" v-model="inputValue" />
    </label>
</template>

<script setup>
import { useModelWrapper } from "../../../libs/useModelWrapper.js";

const props = defineProps({
    modelValue: String,
    label: String,
    helpText: String,
    errorMsg: String,
});
const emit = defineEmits(["update:modelValue"]);

const inputValue = useModelWrapper(props, emit);
</script>
