<template>
    <div>
        <div v-for="category in categories" :key="category.uuid">
            <p class="text-lg font-semibold">{{ category.name }}</p>
            <div class="my-6">
                <div v-for="addon in category.add_ons" :key="addon.uuid">
                    <label class="inline-flex gap-2 items-center">
                        <input
                            type="checkbox"
                            class="text-indigo-500 focus:ring-0"
                            v-model="selectedAddons"
                            :value="addon.id"
                        />
                        <span>{{ addon.name }}</span>
                    </label>
                </div>
            </div>
        </div>
        <div>
            <SubmitButton :waiting="saving" @click="save">Save</SubmitButton>
        </div>
    </div>
</template>

<script setup>
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import { httpAction } from "../../../libs/httpAction";
import { showError, showSuccess } from "../../../libs/notifications";
import SubmitButton from "../UI/SubmitButton.vue";

const props = defineProps({ menu: Object });

const store = useStore();

const categories = computed(() => store.state.addons.categories);

const selectedAddons = ref(props.menu.add_ons.map((a) => a.id));

const [saving, save] = httpAction(
    () =>
        store.dispatch("menus/assignAddOns", {
            menu_id: props.menu.id,
            add_on_ids: selectedAddons.value,
        }),
    () => showSuccess("Add-ons updated"),
    () => showError("Failed to set add-ons")
);

onMounted(() => {
    store.dispatch("addons/fetchCategories");
});
</script>
