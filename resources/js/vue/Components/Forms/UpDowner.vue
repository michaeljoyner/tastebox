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
                'text-green-600': value >= 1,
                'text-gray-500': value === 0,
            }"
        >
            {{ value }}
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
    props: ["value", "options"],

    methods: {
        down() {
            if (this.options.indexOf(this.value) === 0) {
                return;
            }
            this.$emit(
                "input",
                this.options[this.options.indexOf(this.value) - 1]
            );
        },

        up() {
            if (this.options.indexOf(this.value) === this.options.length - 1) {
                return;
            }
            this.$emit(
                "input",
                this.options[this.options.indexOf(this.value) + 1]
            );
        },
    },
};
</script>
