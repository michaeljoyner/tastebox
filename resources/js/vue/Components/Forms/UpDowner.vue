<template>
    <div class="flex items-center justify-center">
        <button
            type="button"
            class="text-2xl mx-2 font-bold focus:outline-none hover:bg-gray-200 rounded-full"
            @click="down"
        >
            -
        </button>
        <p
            class="text-2xl"
            :class="{
                'text-green-600': modelValue >= 1,
                'text-gray-500': modelValue === 0,
            }"
        >
            {{ modelValue }}
        </p>
        <button
            type="button"
            class="text-2xl mx-2 font-bold focus:outline-none hover:bg-gray-200 rounded-full"
            @click="up"
        >
            +
        </button>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["modelValue", "options"],
    emits: ["update:modelValue"],

    setup(props, { emit }) {
        const up = () => {
            if (
                props.options.indexOf(props.modelValue) ===
                props.options.length - 1
            ) {
                return;
            }
            emit(
                "update:modelValue",
                props.options[props.options.indexOf(props.modelValue) + 1]
            );
        };

        const down = () => {
            if (props.options.indexOf(props.modelValue) === 0) {
                return;
            }
            emit(
                "input",
                props.options[props.options.indexOf(props.modelValue) - 1]
            );
        };

        return { up, down };
    },
};
</script>
