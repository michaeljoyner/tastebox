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
            <input
                ref="input"
                :type="safeType"
                v-model="inputValue"
                :placeholder="placeholder"
                class="form-input"
            />
        </label>
    </div>
</template>

<script type="text/babel">
import { useModelWrapper } from "../../../libs/useModelWrapper";
import { computed } from "vue";

export default {
    props: [
        "modelValue",
        "error-msg",
        "input-name",
        "label",
        "type",
        "help-text",
        "placeholder",
    ],
    emit: ["update:modelValue"],

    setup(props, { emit }) {
        const safeType = computed(() => {
            const options = {
                password: "password",
                email: "email",
                text: "text",
            };

            return options[props.type] || "text";
        });
        return {
            inputValue: useModelWrapper(props, emit),
            safeType,
        };
    },
};
</script>
