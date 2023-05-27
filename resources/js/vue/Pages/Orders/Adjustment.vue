<template>
    <page v-if="adjustment">
        <page-header :title="`Adjustment #${adjustment.id}`">
            <router-link to="/adjustments" class="muted-text-btn"
                >&larr; Back to Adjustments</router-link
            >
        </page-header>

        <div class="my-12 max-w-3xl shadow-lg p-6 rounded-xl">
            <div class="flex justify-between items-center mb-4">
                <colour-label
                    :colour="adjustment.is_resolved ? 'green' : 'red'"
                    :text="adjustment.status"
                ></colour-label>

                <colour-label
                    :colour="adjustment.value_in_cents >= 0 ? 'green' : 'red'"
                    :text="adjustment.amount"
                ></colour-label>
            </div>

            <div class="flex items-center space-x-6">
                <p v-show="!adjustment.user_id" class="text-sm">
                    {{ adjustment.customer_name }}
                </p>
                <div
                    v-show="adjustment.user_id"
                    class="text-sm flex space-x-1 items-center"
                >
                    <div class="w-2 h-2 bg-indigo-700 rounded-full"></div>
                    <router-link
                        :to="`/memberships/members/${adjustment.user_id}/show`"
                        class="hover:text-blue-500"
                        >{{ adjustment.customer_name }}</router-link
                    >
                </div>
                <p class="text-sm">{{ adjustment.customer_email }}</p>
                <p class="text-sm">{{ adjustment.customer_phone }}</p>
            </div>

            <div v-show="adjustment.is_resolved">
                <p class="text-sm text-gray-500 my-3">
                    Resolved on {{ adjustment.resolved_at }} by
                    {{ adjustment.resolvor }}
                </p>

                <p class="text-gray-500 text-xs uppercase mt-6 mb-1">Note</p>
                <p class="mb-6">
                    {{ adjustment.resolution_note || "No note was given" }}
                </p>
            </div>

            <div class="mt-6" v-show="!adjustment.is_resolved">
                <p class="text-lg">{{ summary }}</p>
                <p class="text-gray-500 text-xs uppercase mt-6 mb-1">Reason</p>
                <p class="mb-6">{{ adjustment.reason }}</p>
                <button class="btn btn-main" @click="showResolveModal = true">
                    Resolve
                </button>
            </div>

            <div class="pt-3 mt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Created on {{ adjustment.created_at }} by
                    {{ adjustment.creator }}
                </p>
            </div>
        </div>

        <modal :show="showResolveModal" @close="showResolveModal = false">
            <div class="max-w-2xl mx-auto p-6 rounded-lg bg-white">
                <p class="font-semibold text-indigo-600 text-xl">
                    Resolve this Adjustment
                </p>

                <p class="my-4 text-gray-500 text-sm">
                    This will mark this adjustment to be resolved by you. That
                    means you take responsibility for it, and are willing to
                    stand by your most likely dubious actions. You may leave a
                    brief note if you wish.
                </p>

                <text-area-field label="Note" v-model="note"></text-area-field>

                <div class="justify-end mt-6 flex space-x-6">
                    <button
                        class="muted-text-btn"
                        @click="showResolveModal = false"
                    >
                        Cancel
                    </button>
                    <submit-button :waiting="resolving" @click="resolve"
                        >Resolve</submit-button
                    >
                </div>
            </div>
        </modal>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import Modal from "../../Components/Modal.vue";
import InputField from "../../Components/Forms/InputField.vue";
import TextAreaField from "../../Components/Forms/TextAreaField.vue";
import { httpAction } from "../../../libs/httpAction.js";
import { showError, showSuccess } from "../../../libs/notifications.js";
import SubmitButton from "../../Components/UI/SubmitButton.vue";
export default {
    components: {
        SubmitButton,
        TextAreaField,
        InputField,
        Modal,
        ColourLabel,
        PageHeader,
        Page,
    },

    setup() {
        const store = useStore();
        const route = useRoute();
        const router = useRouter();

        const adjustment = computed(() => store.state.adjustments.active);

        const summary = computed(() => {
            return adjustment.value.credit
                ? `Tastebox needs to claim ${adjustment.value.amount} from ${adjustment.value.customer_name}`
                : `Tastebox needs to pay ${adjustment.value.customer_name} to grand sum of  ${adjustment.value.amount}`;
        });

        onMounted(() => {
            store.dispatch("adjustments/fetchById", route.params.adjustment);
        });

        const showResolveModal = ref(false);
        const note = ref("");

        const [resolving, resolve] = httpAction(
            () =>
                store.dispatch("adjustments/resolve", {
                    adjustment_id: adjustment.value.id,
                    note: note.value,
                }),
            () => {
                showSuccess("Adjustment resolved");
                router.push("/adjustments");
            },
            () => showError("Failed to resolve adjustment")
        );

        return {
            adjustment,
            summary,
            showResolveModal,
            note,
            resolving,
            resolve,
        };
    },
};
</script>
