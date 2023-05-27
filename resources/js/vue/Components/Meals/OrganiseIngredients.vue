<template>
    <div>
        <sub-header title="Organise Ingredients">
            <button class="btn mx-4" @click="showModal = true" type="button">
                Add Group
            </button>
            <button type="button" class="btn btn-main" @click="save">
                Save
            </button>
        </sub-header>

        <p class="my-8 text-gray-600 text-sm">
            Drag ingredients into their correct order and list.
            <strong>Dont forget to save</strong> when you are done.
        </p>

        <div v-for="group in groups" class="my-12 shadow p-6">
            <div
                class="flex justify-between pb-2 mb-3 border-b border-gray-300"
            >
                <p class="">
                    <span class="tex-lg capitalize font-semibold">{{
                        group.name
                    }}</span>
                    <span
                        v-show="group.bundled"
                        class="text-gray-600 text-sm mr-2"
                        >(Bundled on recipe card as "{{ group.name }}")</span
                    >
                </p>
                <div class="flex justify-end">
                    <button
                        v-if="group.name.toLowerCase() !== 'main'"
                        @click="prepareRenameGroup(group)"
                        class="text-gray-600 hover:text-blue-600 text-sm"
                    >
                        Rename
                    </button>
                    <button
                        v-if="group.name.toLowerCase() !== 'main'"
                        type="button"
                        @click="deleteGroup(group)"
                        class="text-gray-600 hover:text-blue-600 text-sm ml-4"
                    >
                        Delete
                    </button>
                </div>
            </div>

            <div
                :ref="assignSortableRef(group)"
                style="min-height: 5rem"
                class=""
            >
                <div
                    v-for="ingredient in group.ingredients"
                    :key="ingredient.meal_ingredient_id"
                    :data-id="ingredient.meal_ingredient_id"
                    style="cursor: grab"
                    class="mb-1 flex items-center"
                >
                    <span>&middot;</span>
                    <p class="w-12 mx-3 text-xs text-gray-600">
                        {{ ingredient.quantity }}
                    </p>
                    <p class="flex-1 font-semibold text-lg">
                        {{ ingredient.description }}
                    </p>
                </div>
            </div>
        </div>
        <modal :show="showModal" @close="showModal = false">
            <form
                @submit.prevent="addGroup"
                class="w-full mx-auto bg-white max-w-md p-6"
            >
                <p class="font-bold">Add new ingredient group</p>
                <input-field
                    class="mt-6 mb-2"
                    label="Name of group"
                    v-model="group_name"
                ></input-field>
                <div class="mb-6">
                    <input
                        type="checkbox"
                        v-model="bundle_new_group"
                        id="new_bundle_check"
                    />
                    <label for="new_bundle_check"
                        >Bundle this group on the recipe card?</label
                    >
                </div>
                <div class="flex justify-end">
                    <button
                        class="btn mx-4"
                        type="button"
                        @click="showModal = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="!group_name"
                        class="btn btn-main"
                    >
                        Add Group
                    </button>
                </div>
            </form>
        </modal>
        <modal :show="showRenameModal" @close="showRenameModal = false">
            <form
                @submit.prevent="renameGroup"
                class="w-full mx-auto bg-white max-w-md p-6"
            >
                <p class="font-bold">Rename this group</p>
                <input-field
                    class="mt-6 mb-2"
                    label="Name of group"
                    v-model="rename_name"
                ></input-field>
                <div class="mb-6">
                    <input
                        type="checkbox"
                        v-model="bundle_renamed"
                        id="rename_bundle_check"
                    />
                    <label for="rename_bundle_check"
                        >Bundle this group on the recipe card?</label
                    >
                </div>
                <div class="flex justify-end">
                    <button
                        class="btn mx-4"
                        type="button"
                        @click="showRenameModal = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="!rename_name"
                        class="btn btn-main"
                    >
                        Rename Group
                    </button>
                </div>
            </form>
        </modal>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader.vue";
import Modal from "../Modal.vue";
import InputField from "../Forms/InputField.vue";
import Sortable from "sortablejs";
import { showError, showSuccess } from "../../../libs/notifications.js";
export default {
    components: { InputField, SubHeader, Modal },

    props: ["meal"],

    data() {
        return {
            groups: this.meal.ingredients.reduce((groups, ingredient) => {
                const groupName = ingredient.group || "main";
                const existing = groups.find((g) => g.name === groupName);
                if (existing) {
                    existing.ingredients.push(ingredient);
                    return groups;
                }
                groups.push({
                    ingredients: [ingredient],
                    name: groupName,
                    key: groupName.replace(" ", "_").toLowerCase(),
                    bundled: ingredient.bundled,
                });
                return groups;
            }, []),
            group_name: "",
            bundle_new_group: false,
            rename_name: "",
            bundle_renamed: false,
            renaming_group: null,
            showModal: false,
            showRenameModal: false,
            sortables: [],
        };
    },

    mounted() {
        this.assignGroupSortables();
    },

    methods: {
        assignSortableRef(group) {
            return group.key;
        },

        addGroup() {
            this.showModal = false;
            this.groups = [
                ...this.groups,
                {
                    ingredients: [],
                    name: this.group_name,
                    key: this.group_name.replace(" ", "_").toLowerCase(),
                    bundled: this.bundle_new_group,
                },
            ];
            this.group_name = "";
            this.bundle_new_group = false;
            this.$nextTick().then(this.assignGroupSortables);
        },

        assignGroupSortables() {
            this.sortables.forEach((s) => s.sortable.destroy());
            this.sortables = [];
            this.groups.forEach((group) => {
                this.sortables.push({
                    name: group.name,
                    bundled: group.bundled,
                    sortable: Sortable.create(this.$refs[group.key][0], {
                        group: "ingredients",
                    }),
                });
            });
        },

        prepareRenameGroup(group) {
            if (group.name.toLowerCase() === "main") {
                return;
            }
            this.renaming_group = group;
            this.rename_name = group.name;
            this.bundle_renamed = group.bundled;
            this.showRenameModal = true;
        },

        renameGroup() {
            if (this.rename_name.toLowerCase() !== "main") {
                this.renaming_group.name = this.rename_name;
                this.renaming_group.bundled = this.bundle_renamed;
                this.renaming_group.key = this.rename_name
                    .replace(" ", "_")
                    .toLowerCase();
                this.$nextTick().then(this.assignGroupSortables);
            }

            this.showRenameModal = false;
            this.rename_name = "";
            this.bundle_renamed = false;
            this.renaming_group = null;
        },

        save() {
            const updated = [];
            this.sortables.forEach((s) => {
                s.sortable.toArray().forEach((id, position) => {
                    const ingredient = this.meal.ingredients.find(
                        (i) => i.meal_ingredient_id === parseInt(id)
                    );
                    updated.push({
                        id: ingredient.id,
                        meal_ingredient_id: id,
                        position: position + 1,
                        group: s.name,
                        bundled: s.bundled,
                    });
                });
            });
            this.$store
                .dispatch("meals/organiseIngredients", {
                    meal_id: this.meal.id,
                    formData: updated,
                })
                .then(() => showSuccess("Saved"))
                .catch(() => showError("Failed to save"));
        },

        deleteGroup(group) {
            if (group.name.toLowerCase() === "main") {
                return;
            }

            const main = this.groups.find(
                (g) => g.name.toLowerCase() === "main"
            );
            group.ingredients.forEach((ing) => main.ingredients.push(ing));

            this.groups = this.groups.filter((g) => g.key !== group.key);

            this.assignGroupSortables();
        },
    },
};
</script>
