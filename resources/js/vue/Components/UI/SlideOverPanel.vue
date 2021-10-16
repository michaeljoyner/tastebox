<template>
    <teleport to="#side-panels">
        <div
            class="transform transform-gpu w-screen md:w-96 bg-gray-100 h-screen fixed top-0 bottom-0 right-0 overflow-y-auto transition px-4 z-40"
            :class="translateClasses"
        >
            <div class="flex p-2 justify-end">
                <button
                    class="text-3xl text-gray-500 hover:text-blue-500 focus:outline-none"
                    @click="close"
                >
                    &times;
                </button>
            </div>
            <slot> </slot>
        </div>
    </teleport>
</template>

<script type="text/babel">
import { computed, onBeforeUnmount, onMounted } from "vue";

export default {
    props: ["show"],

    emits: ["close"],

    setup(props, { emit }) {
        const translateClasses = computed(() => {
            return !props.show
                ? "translate-x-96"
                : "translate-x-0  shadow-left";
        });

        const close = () => {
            emit("close");
        };

        const closeOnEsc = ({ key }) => {
            if (key === "Escape") {
                emit("close");
            }
        };

        onMounted(() => {
            window.addEventListener("keydown", closeOnEsc);
        });

        onBeforeUnmount(() => {
            window.removeEventListener("keydown", closeOnEsc);
        });

        return { translateClasses, close };
    },
};
</script>
