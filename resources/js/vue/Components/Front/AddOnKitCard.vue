<template>
    <div class="bg-white mb-6 shadow relative">
        <CheckIcon
            v-if="kitQty > 0"
            class="text-green-500 h-6 absolute top-0 right-0 mt-1 mr-1 bg-opaque rounded-full"
        ></CheckIcon>
        <div class="flex flex-col md:flex-row">
            <div class="w-full h-[250px] md:w-[300px] md:h-[200px] shrink-0">
                <img
                    :src="addOn.image"
                    class="w-full h-full object-cover"
                    alt=""
                />
            </div>
            <div class="p-3">
                <p class="type-h3 mt-3 md:mt-0 mb-6">{{ addOn.name }}</p>
                <p class="type-b3">{{ addOn.description }}</p>
            </div>
        </div>
        <div
            class="flex justify-end border-t border-gray-200 py-4 md:py-2 px-4"
        >
            <div v-show="!showButtons" class="flex items-center gap-3">
                <p class="type-b3" v-show="kitQty > 0">
                    {{ kitQty }} in your box
                </p>
                <button
                    type="button"
                    @click="showButtons = true"
                    class="type-b4"
                >
                    {{ kitQty === 0 ? "+ Add to Cart" : "Change" }}
                </button>
            </div>
            <div
                class="flex flex-col md:flex-row items-end md:items-center justify-end gap-3"
                v-show="showButtons"
            >
                <p class="type-b3">How many would you like?</p>

                <div class="flex gap-4">
                    <button
                        :disabled="kitQty === 0"
                        type="button"
                        class="w-6 h-6 border border-black rounded hover:bg-green-300 flex items-center justify-center text-sm"
                        @click="removeFromCart"
                    >
                        0
                    </button>
                    <button
                        :disabled="kitQty === 1"
                        :class="{ 'bg-gray-400': kitQty === 1 }"
                        type="button"
                        class="w-6 h-6 border border-black rounded hover:bg-green-300 flex items-center justify-center text-sm"
                        @click="addToCart(1)"
                    >
                        1
                    </button>
                    <button
                        :disabled="kitQty === 2"
                        :class="{ 'bg-gray-400': kitQty === 2 }"
                        type="button"
                        class="w-6 h-6 border border-black rounded hover:bg-green-300 flex items-center justify-center text-sm"
                        @click="addToCart(2)"
                    >
                        2
                    </button>
                    <button
                        :disabled="kitQty === 3"
                        :class="{ 'bg-gray-400': kitQty === 3 }"
                        type="button"
                        class="w-6 h-6 border border-black rounded hover:bg-green-300 flex items-center justify-center text-sm"
                        @click="addToCart(3)"
                    >
                        3
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import CheckIcon from "../UI/Icons/CheckIcon.vue";

const props = defineProps({ addOn: Object, kitId: String, initialQty: Number });
const emit = defineEmits(["updated"]);

const kitQty = ref(props.initialQty || 0);
const showButtons = ref(false);
const adding = ref(false);
const removing = ref(false);

const addToCart = (qty) => {
    adding.value = true;
    axios
        .post(`/my-kits/${props.kitId}/add-ons`, {
            add_on_id: props.addOn.id,
            qty,
        })
        .then(({ data }) => {
            adding.value = false;
            kitQty.value = qty;
            showButtons.value = false;
            emit("updated", data);
        })
        .catch((err) => {
            console.log({ err });
            adding.value = false;
        });
};

const removeFromCart = () => {
    removing.value = false;
    axios
        .delete(`/my-kits/${props.kitId}/add-ons/${props.addOn.uuid}`)
        .then(({ data }) => {
            removing.value = false;
            kitQty.value = 0;
            showButtons.value = false;
            emit("updated", data);
        })
        .catch((err) => {
            removing.value = false;
            console.log({ err });
        });
};
</script>
