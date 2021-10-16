<template>
    <div class="flex justify-between mt-0 mb-12">
        <h1 class="text-3xl font-black text-gray-800">{{ title }}</h1>
        <div class="flex items-center justify-end">
            <slot></slot>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["title"],
};
</script>
