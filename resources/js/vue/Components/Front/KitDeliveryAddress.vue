<template>
    <div class="border-t border-b md:border-b-0 border-green-600 mt-3 py-3">
        <div v-show="!kit.can_deliver">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="flex space-x-3 items-center">
                    <TruckIcon class="text-green-400 w-5 h-5" />
                    <p class="text-gray-500">
                        Where shall we delivery this to?
                    </p>
                </div>

                <button
                    @click="showEditModal = true"
                    class="text-sm font-semibold text-white bg-green-500 hover:bg-green-700 rounded-full shadow-md px-8 py-2 mt-4 md:mt-0"
                >
                    Set Address
                </button>
            </div>
        </div>

        <div
            v-show="kit.can_deliver"
            class="flex flex-col items-start md:items-center md:flex-row justify-between"
        >
            <div>
                <p class="text-xs uppercase">
                    Delivery to {{ kit.delivery_area }}
                </p>
                <p>{{ kit.delivery_address }}</p>
                <p class="text-xs text-gray-500" v-show="kit.deliver_with">
                    Will be delivered with {{ kit.deliver_with }}
                </p>
                <p class="text-xs uppercase"></p>
            </div>
            <button
                @click="showEditModal = true"
                class="inline-block mt-4 md:mt-0 text-sm font-semibold text-gray-600 hover:text-green-600 focus:outline-none bg-gray-100 rounded-full px-4 py-1"
            >
                Change Address
            </button>
        </div>

        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="max-w-md mx-auto bg-white shadow-lg p-6 rounded-lg">
                <p class="type-h3 mb-6">Where shall we deliver this box to?</p>

                <div v-show="has_errors">
                    <p class="text-sm text-red-500 my-6">
                        There was a problem setting the address. Please check
                        you have correct input, or refresh and try again later.
                        Thanks.
                    </p>
                </div>
                <div
                    v-for="address in alternateAddresses"
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
                            {{ address.delivery_area.value }}
                        </p>
                    </label>
                </div>
                <div
                    class="flex justify-center items-center my-4"
                    v-show="alternateAddresses.length"
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
                            <option
                                v-for="area in availableAreas"
                                :key="area.key"
                                :value="area.key"
                                >{{ area.description }}</option
                            >
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
                <div v-show="oneOfMany" class="mt-3">
                    <label
                        for="apply-to-all"
                        class="flex items-center space-x-2"
                    >
                        <input
                            type="checkbox"
                            v-model="apply_to_all_unset"
                            class="text-green-500 focus:outline-none focus:ring-green-500"
                        />
                        <span>Apply to all boxes without an address</span>
                    </label>
                </div>
                <div class="flex items-center justify-end mt-6 space-x-4">
                    <button
                        class="text-sm text-gray-500 hover:text-gray-700"
                        @click="showEditModal = false"
                    >
                        Cancel
                    </button>
                    <button
                        class="green-btn"
                        :disabled="!canSubmit"
                        @click="save"
                    >
                        <span v-show="saving">
                            <SpinningIcon
                                class="text-white w-5 h-5 animate-spin inline-block mr-3"
                            />
                            Saving...
                        </span>
                        <span v-show="!saving">Save Address</span>
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script type="text/babel">
import Modal from "../Modal";
import { ref, computed } from "vue";
import { httpAction } from "../../../libs/httpAction";
import SpinningIcon from "../Icons/SpinningIcon";
import WarningIcon from "../Icons/WarningIcon";
import TruckIcon from "../Icons/TruckIcon";
export default {
    components: { TruckIcon, WarningIcon, SpinningIcon, Modal },
    props: ["kit", "suggested-addresses", "available-areas", "one-of-many"],

    setup(props, { emit }) {
        const showEditModal = ref(false);

        const selected_address = ref(null);
        const new_area = ref(null);
        const new_address = ref("");
        const has_errors = ref(false);
        const apply_to_all_unset = ref(props.oneOfMany);

        const canSubmit = computed(() => {
            return (
                !!selected_address.value ||
                (new_area.value && new_address.value)
            );
        });

        const alternateAddresses = computed(() =>
            props.suggestedAddresses.filter((a) => a.kit_id !== props.kit.id)
        );

        const formData = computed(() => {
            if (selected_address.value) {
                return {
                    type: "kit",
                    kit_id: selected_address.value,
                    apply_to_all_unset: false,
                };
            }

            return {
                type: "address",
                delivery_area: new_area.value,
                delivery_address: new_address.value,
                apply_to_all_unset: props.oneOfMany && apply_to_all_unset.value,
            };
        });

        const [saving, save] = httpAction(
            async () => {
                has_errors.value = false;
                const { data } = await axios.post(
                    `/api/kits/${props.kit.id}/delivery-address`,
                    formData.value
                );
                return data;
            },
            (data) => {
                showEditModal.value = false;
                emit("updated", data);
            },
            (err) => {
                has_errors.value = true;
            }
        );

        return {
            showEditModal,
            apply_to_all_unset,
            selected_address,
            new_area,
            new_address,
            canSubmit,
            saving,
            save,
            has_errors,
            alternateAddresses,
        };
    },
};
</script>
