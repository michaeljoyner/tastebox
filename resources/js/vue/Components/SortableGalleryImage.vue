<template>
    <div
        :data-id="image.id"
        class="w-48 h-32 m-6 relative"
        :class="{ 'opactity-50': waiting }"
    >
        <img
            :src="image.src"
            class="w-full h-full object-cover"
            @error="retryImage"
        />
        <button
            :disabled="waiting"
            class="absolute top-0 right-0 -m-2 bg-red-500 text-white h-6 w-6 rounded-full"
            @click="deleteImage"
        >
            &times;
        </button>
    </div>
</template>

<script type="text/babel">
import { showError } from "../../libs/notifications";

export default {
    props: ["image", "delete-url"],

    data() {
        return {
            waiting: false,
        };
    },

    methods: {
        deleteImage() {
            this.waiting = true;
            axios
                .delete(this.deleteUrl)
                .then(() => {
                    this.$emit("deleted");
                })
                .catch(() => {
                    showError("Unable to delete image.");
                    this.waiting = false;
                });
        },

        retryImage(ev) {
            window.setTimeout(() => {
                ev.target.src = `${ev.target.src}?rnd=${Math.random()}`;
            }, 1000);
        },
    },
};
</script>
