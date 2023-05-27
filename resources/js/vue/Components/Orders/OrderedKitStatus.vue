<template>
    <colour-label :colour="status_colour" :text="status"></colour-label>
</template>

<script type="text/babel">
import ColourLabel from "../UI/ColourLabel.vue";

export default {
    components: {
        ColourLabel,
    },

    props: ["status"],

    computed: {
        status_colour() {
            const map = {
                due: "green",
                done: "purple",
                cancelled: "red",
            };

            return map[this.status];
        },
    },
};
</script>
