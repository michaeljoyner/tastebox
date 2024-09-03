<template>
    <button :disabled="recentlyCopied" class="focus:ring-0 focus:outline-none" @click="copy">{{ displayText}}</button>
</template>

<script setup>

import {computed, ref} from "vue";

const props = defineProps({
    text: String,
    copyText: String,
})

const recentlyCopied = ref(false);

const displayText = computed(() => recentlyCopied.value ? "Copied" : props.text);
const copy = async () => {
    try {
        await navigator.clipboard.writeText(props.copyText);
        recentlyCopied.value = true;
        window.setTimeout(() => recentlyCopied.value = false, 1000);
    } catch (e) {
        console.error(e);
    }

}
</script>

