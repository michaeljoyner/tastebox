<template>
    <page v-if="kit">
        <page-header title="Ordered Kit">
            <router-link
                v-show="!kit.is_cancelled"
                :to="`/ordered-kits/${kit.id}/edit`"
                class="muted-text-btn"
                >Adjust Kit</router-link
            >
            <button
                v-show="!kit.is_cancelled"
                @click="showCancelModal = true"
                class="muted-text-btn ml-4"
            >
                Cancel
            </button>
        </page-header>

        <div class="my-12">
            <div class="divide-x space-x-4">
                <span class="text-xl font-semibold">{{
                    kit.customer_name
                }}</span>
                <span class="text-xl font-semibold pl-4"
                    >MENU #{{ kit.menu_week }}</span
                >
            </div>

            <p class="my-2 text-gray-500">{{ kit.address }}</p>
            <div>
                <colour-label
                    v-show="!kit.is_cancelled"
                    colour="green"
                    :text="`Delivery on: ${kit.delivery_date}`"
                ></colour-label>

                <colour-label
                    v-show="kit.is_cancelled"
                    colour="red"
                    text="Cancelled"
                ></colour-label>
            </div>
        </div>

        <div class="my-12">
            <p class="text-xs uppercase text-gray-500 mb-3">Ordered Meals</p>
            <div
                v-for="meal in kit.meals"
                class="my-2 border-b border-gray-200 pb-2"
            >
                <span>{{ meal.servings }} x </span>
                <span>{{ meal.name }}</span>
            </div>
        </div>

        <div class="my-12">
            <p class="text-xs uppercase text-gray-500 mb-3">Ordered Add-Ons</p>
            <div
                v-for="add_on in kit.add_ons"
                class="my-2 border-b border-gray-200 pb-2"
            >
                <span>{{ add_on.qty }} x </span>
                <span>{{ add_on.name }}</span>
            </div>
        </div>

        <modal :show="showCancelModal" @close="showCancelModal = false">
            <form
                @submit.prevent="cancel"
                class="w-full max-w-lg mx-auto p-6 bg-white rounded-lg"
            >
                <p class="text-lg font-semibold">Cancel This Kit?</p>
                <p class="my-6 text-gray-500">
                    This will mark this kit as cancelled, and create a new
                    adjustment for the value of meals. Please provide a reason
                    for the cancellation.
                </p>
                <input-field
                    class="my-6"
                    v-model="cancellation_reason"
                    :error="cancellation_error"
                    label="Reason"
                ></input-field>
                <div class="mt-6 flex items-center justify-end space-x-4">
                    <button type="button" @click="showCancelModal = false">
                        Don't do it
                    </button>
                    <submit-button :waiting="cancelling"
                        >Cancel Kit</submit-button
                    >
                </div>
            </form>
        </modal>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { computed, onMounted, ref } from "vue";
import { showError, showSuccess } from "../../../libs/notifications.js";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import Modal from "../../Components/Modal.vue";
import { httpAction } from "../../../libs/httpAction.js";
import InputField from "../../Components/Forms/InputField.vue";
import SubmitButton from "../../Components/UI/SubmitButton.vue";
export default {
    components: {
        SubmitButton,
        InputField,
        Modal,
        ColourLabel,
        Page,
        PageHeader,
    },

    setup() {
        const store = useStore();
        const route = useRoute();

        const kit = computed(() => store.state.kits.active);

        const showCancelModal = ref(false);
        const cancellation_reason = ref("");
        const cancellation_error = ref("");

        const [cancelling, cancel] = httpAction(
            () => {
                cancellation_error.value = "";
                return store.dispatch("kits/cancel", {
                    kit_id: kit.value.id,
                    reason: cancellation_reason.value,
                });
            },
            () => {
                showSuccess("Kit has been cancelled");
                showCancelModal.value = false;
            },
            (resp) => {
                if (resp.data.status === 422) {
                    return (cancellation_error.value =
                        "please provide a reason");
                }
                showError("Failed to cancel kit");
            }
        );

        onMounted(() => {
            store.dispatch("kits/fetchActive", route.params.kit);
        });

        return {
            kit,
            showCancelModal,
            cancellation_reason,
            cancellation_error,
            cancelling,
            cancel,
        };
    },
};
</script>
