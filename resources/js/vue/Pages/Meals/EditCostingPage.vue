<template>
    <div>
        <SubHeader title="Edit this Costing">
            <RouterLink
                :to="`/meals/${meal.id}/manage/costings`"
                class="muted-text-btn"
                >&larr; Back</RouterLink
            >
            <button class="mx-4 muted-text-btn" @click="showDelete = true">
                Delete
            </button>
        </SubHeader>

        <div v-if="costing">
            <MealCostingForm
                :meal-id="meal.id"
                :costing="costing"
                @saved="$router.push(`/meals/${meal.id}/manage/costings`)"
            />
        </div>

        <Modal :show="showDelete" @close="showDelete = false">
            <div class="w-screen max-w-md mx-auto p-6 bg-white rounded-lg">
                <p class="text-lg font-semibold">Delete this costing?</p>
                <p class="text-gray-500 my-4">
                    This will delete this costing, and it will be as if it never
                    happened. Don't forget to check that the meal tier is
                    correct.
                </p>
                <div class="mt-6 flex justify-end items-center space-x-4">
                    <button
                        type="button"
                        @click="showDelete = false"
                        class="muted-text-btn"
                    >
                        Cancel
                    </button>
                    <SubmitButton
                        type="button"
                        :waiting="removing"
                        @click="remove"
                        >Yes, delete it</SubmitButton
                    >
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import SubHeader from "../../Components/UI/SubHeader.vue";
import MealCostingForm from "../../Components/Meals/MealCostingForm.vue";
import { useRoute, useRouter } from "vue-router";
import { computed, ref } from "vue";
import Modal from "../../Components/Modal.vue";
import { httpAction } from "../../../libs/httpAction";
import { useStore } from "vuex";
import { showError } from "../../../libs/notifications";
import SubmitButton from "../../Components/UI/SubmitButton.vue";

const props = defineProps({ meal: Object });

const store = useStore();
const route = useRoute();
const router = useRouter();

const costing = computed(() =>
    props.meal.costings.find((c) => c.id === parseInt(route.params.costing))
);

const showDelete = ref(false);
const [removing, remove] = httpAction(
    () => store.dispatch("meals/deleteCosting", costing.value.id),
    () => {
        showDelete.value = false;
        router.push(`/meals/${props.meal.id}/manage/costings`);
    },
    () => showError("Failed to delete costing")
);
</script>
