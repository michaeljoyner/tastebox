<template>
    <teleport to="#modals">
        <div
            class="fixed z-50 inset-0 bg-black bg-opacity-50 flex justify-center items-center"
            v-show="show"
        >
            <div class="m-6 w-full rounded-lg">
                <slot></slot>
            </div>
        </div>
    </teleport>
</template>

<script type="text/babel">
import { onBeforeUnmount, onMounted } from "vue";

export default {
    props: ["show"],
    emits: ["close"],

    setup(props, { emit }) {
        const listenForEsc = ({ key }) => {
            if (key === "Escape") {
                emit("close");
            }
        };
        onMounted(() => {
            window.addEventListener("keydown", listenForEsc);
        });

        onBeforeUnmount(() => {
            window.removeEventListener("keydown", listenForEsc);
        });
    },
};
</script>
