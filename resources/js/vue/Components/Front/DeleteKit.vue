<template>
    <span>
        <button
            @click="showForm = true"
            class="text-gray-600 hover:text-red-600 font-bold mr-4"
        >
            Remove kit
        </button>
        <modal :show="showForm" @close="showForm = false">
            <div class="w-full max-w-lg p-6">
                <p class="type-h2 text-red-600">Are You sure?</p>
                <p class="my-6">
                    You are about to remove {{ kitName }} from your basket. Are
                    you sure you want to proceed?
                </p>
                <div class="flex justify-end mt-6">
                    <button
                        @click="showForm = false"
                        class="text-gray-600 mr-4"
                    >
                        Cancel
                    </button>
                    <submit-button
                        @click.native="removeKit"
                        :waiting="waiting"
                        mode="danger"
                    >
                        Yes, remove it!
                    </submit-button>
                </div>
            </div>
        </modal>
    </span>
</template>

<script type="text/babel">
import SubmitButton from "../UI/SubmitButton";
export default {
    components: {
        SubmitButton,
    },

    props: ["kit-name", "kit-id"],

    data() {
        return {
            waiting: false,
            showForm: false,
        };
    },

    methods: {
        removeKit() {
            this.waiting = true;
            axios
                .delete(`/my-kits/${this.kitId}`)
                .then(({ data }) => {
                    this.$emit("deleted", data.kits);
                })
                .catch(console.log)
                .then(() => {
                    this.showForm = false;
                    this.waiting = false;
                });
        },
    },
};
</script>
