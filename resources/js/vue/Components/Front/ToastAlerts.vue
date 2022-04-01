<template>
    <transition
        enter-active-class="translate-x-0 opacity-100"
        enter-from-class="translate-x-80 opacity-0"
        leave-active-class="translate-x-80 opacity-0"
    >
        <div
            v-show="message"
            :class="{
                'bg-green-500': message?.type === 'success',
                'bg-red-500': message?.type === 'error',
            }"
            class="fixed transition ease-in-out transform bottom-0 right-0 text-white text-sm md:text-base max-w-xs md:max-w-sm p-3 md:p-6 rounded-lg shadow mb-6 mr-6 flex space-x-3 items-center"
        >
            <svg
                v-show="message?.type === 'success'"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 fill-current text-green-300"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                />
            </svg>
            <svg
                v-show="message?.type === 'error'"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 fill-current text-red-300"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"
                />
            </svg>
            <span class="font-semibold">{{ message?.text }}</span>
        </div>
    </transition>
</template>

<script type="text/babel">
import { computed, onMounted, ref, watch } from "vue";

export default {
    setup() {
        const queue = ref([]);
        const message = computed(() =>
            queue.value.length ? queue.value[0] : null
        );

        watch(
            () => message.value,
            (new_message) => {
                if (new_message) {
                    window.setTimeout(() => {
                        if (queue.value.length) {
                            queue.value = queue.value.slice(1);
                        }
                    }, 2500);
                }
            }
        );

        const addSuccessMessage = ({ detail: text }) => {
            queue.value.push({ type: "success", text });
        };

        onMounted(() => {
            const toast = window.toastMessage;
            if (toast) {
                queue.value.push(toast);
            }

            window.addEventListener("toasties:success", addSuccessMessage);
        });

        return { message };
    },
};
</script>
