<template>
    <button class="focus:outline-none" :disabled="waiting">
        <span v-if="waiting" class="flex">
            <span
                class="dot-1 dot block w-3 h-3 rounded-full bg-current"
            ></span>
            <span
                class="dot-2 dot block mx-1 w-3 h-3 rounded-full bg-current"
            ></span>
            <span
                class="dot-3 dot block w-3 h-3 rounded-full bg-current"
            ></span>
        </span>
        <span v-else>
            <slot></slot>
        </span>
    </button>
</template>

<script type="text/babel">
export default {
    props: ["waiting"],
};
</script>

<style scoped lang="less">
@keyframes fadedAndBounce {
    from {
        opacity: 0;
        transform: translate3d(0, -5px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(1, 5px, 0);
    }
}

.dot {
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
    animation-direction: alternate;
    animation-duration: 1s;
    animation-name: fadedAndBounce;
}
.dot-1 {
    animation-delay: 0s;
}

.dot-2 {
    animation-delay: 0.3s;
}

.dot-3 {
    animation-delay: 0.6s;
}
</style>
