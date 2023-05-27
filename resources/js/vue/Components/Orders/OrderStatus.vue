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
            const colour_map = {
                created: "indigo",
                pending: "gray",
                open: "green",
                complete: "teal",
            };

            return colour_map[this.status];
        },
    },
};
</script>
