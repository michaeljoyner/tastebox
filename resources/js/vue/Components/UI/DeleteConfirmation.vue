<template>
    <span>
        <button
            :disabled="disabled"
            class="btn btn-red"
            @click="showModal = true"
        >
            Delete
        </button>
        <modal :show="showModal" @close="showModal = false">
            <div class="p-6 max-w-md w-full mx-auto bg-white">
                <p class="font-bold text-lg">Are you very sure?</p>
                <p class="my-6">
                    You are about to delete {{ item }}. This may be
                    irreversible.
                </p>
                <div class="flex justify-end mt-6">
                    <button class="btn mr-4" @click="showModal = false">
                        Cancel
                    </button>
                    <button
                        class="btn btn-red"
                        @click="$emit('confirmed') && (showModal = false)"
                    >
                        Yes, delete it!
                    </button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
import Modal from "../Modal.vue";
export default {
    components: {
        Modal,
    },

    props: ["disabled", "item"],

    data() {
        return {
            showModal: false,
        };
    },
};
</script>
