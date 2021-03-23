import { computed } from "vue";

function useModelWrapper(props, emit) {
    return computed({
        get: () => props.modelValue,
        set: (value) => emit("update:modelValue", value),
    });
}

export { useModelWrapper };
