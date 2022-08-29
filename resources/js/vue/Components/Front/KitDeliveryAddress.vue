<template>
    <div>
        <div v-show="!kit.can_deliver">
            <p>Delivery address not set</p>
            <button @click="showEditModal = true">Set Address</button>
        </div>

        <div v-show="kit.can_deliver">
            <p>
                Deliver to {{ kit.delivery_address }} ({{ kit.delivery_area }})
            </p>
            <button @click="showEditModal = true">Edit</button>
        </div>

        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="max-w-md mx-auto bg-white shadow-lg p-6 rounded-lg">
                <p class="type-h3 mb-6">Where shall we deliver this box to?</p>

                <div
                    v-for="address in suggestedAddresses"
                    class="flex items-center space-x-8 p-4 rounded-lg shadow-md mb-4"
                >
                    <input
                        type="radio"
                        v-model="selected_address"
                        class="text-green-500 focus:outline-none focus:ring-green-500"
                        :value="address.kit_id"
                        :id="`suggested_address_${address.kit_id}_${kit.id}`"
                    />
                    <label
                        :for="`suggested_address_${address.kit_id}_${kit.id}`"
                    >
                        <p>{{ address.delivery_address }}</p>
                        <p class="text-xs uppercase font-semibold">
                            {{ address.delivery_area.key }}
                        </p>
                    </label>
                </div>
                <div
                    class="flex justify-center items-center my-4"
                    v-show="suggestedAddresses.length"
                >
                    <p>or</p>
                </div>
                <div :class="{ 'opacity-50': selected_address }">
                    <div class="mb-4">
                        <label for="">Deliver area</label>
                        <select
                            v-model="new_area"
                            class="w-full block mt-1"
                            @focus="selected_address = null"
                        >
                            <option :value="null"
                                >Select a delivery area</option
                            >
                            <option value="1">Hilton</option>
                            <option value="2">PMB</option>
                            <option value="3">Howick</option>
                        </select>
                    </div>
                    <div>
                        <label for="">Address</label>
                        <input
                            type="text"
                            v-model="new_address"
                            class="w-full block mt-1"
                            @focus="selected_address = null"
                        />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6 space-x-4">
                    <button
                        class="text-sm text-gray-500 hover:text-gray-700"
                        @click="showEditModal = false"
                    >
                        Cancel
                    </button>
                    <button class="green-btn" :disabled="!canSubmit">
                        Save Address
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script type="text/babel">
import Modal from "../Modal";
import { ref, computed } from "vue";
export default {
    components: { Modal },
    props: ["kit", "suggested-addresses"],

    setup(props, { emit }) {
        const showEditModal = ref(false);

        const selected_address = ref(null);
        const new_area = ref(null);
        const new_address = ref("");

        const canSubmit = computed(() => {
            return (
                !!selected_address.value ||
                (new_area.value && new_address.value)
            );
        });

        return {
            showEditModal,
            selected_address,
            new_area,
            new_address,
            canSubmit,
        };
    },
};
</script>
