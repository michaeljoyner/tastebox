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
                <p class="font-semibold capitalize text-lg">{{ group.name }}</p>
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

            <div :ref="group.key" style="min-height: 5rem;" class="">
                <div
                    v-for="ingredient in group.ingredients"
                    :key="ingredient.id"
                    :data-id="ingredient.id"
                    style="cursor: grab;"
                    class="mb-1"
                >
                    <span
                        >&middot; {{ ingredient.quantity }}
                        {{ ingredient.description }}</span
                    >
                </div>
            </div>
        </div>
        <modal :show="showModal" @close="showModal = false">
            <form @submit.prevent="addGroup" class="w-screen max-w-md p-6">
                <p class="font-bold">Add new ingredient group</p>
                <input-field
                    class="my-6"
                    label="Name of group"
                    v-model="group_name"
                ></input-field>
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
            <form @submit.prevent="renameGroup" class="w-screen max-w-md p-6">
                <p class="font-bold">Rename this group</p>
                <input-field
                    class="my-6"
                    label="Name of group"
                    v-model="rename_name"
                ></input-field>
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
import SubHeader from "../UI/SubHeader";
import Modal from "@dymantic/modal";
import InputField from "../Forms/InputField";
import Sortable from "sortablejs";
import { showError, showSuccess } from "../../../libs/notifications";
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
                });
                return groups;
            }, []),
            group_name: "",
            rename_name: "",
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
        addGroup() {
            this.showModal = false;
            this.groups = [
                ...this.groups,
                {
                    ingredients: [],
                    name: this.group_name,
                    key: this.group_name.replace(" ", "_").toLowerCase(),
                },
            ];
            this.group_name = "";
            this.$nextTick().then(this.assignGroupSortables);
        },

        assignGroupSortables() {
            this.sortables.forEach((s) => s.sortable.destroy());
            this.sortables = [];
            this.groups.forEach((group) => {
                this.sortables.push({
                    name: group.name,
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
            this.showRenameModal = true;
        },

        renameGroup() {
            if (this.rename_name.toLowerCase() !== "main") {
                this.renaming_group.name = this.rename_name;
                this.renaming_group.key = this.rename_name
                    .replace(" ", "_")
                    .toLowerCase();
                this.assignGroupSortables();
            }

            this.showRenameModal = false;
            this.rename_name = "";
            this.renaming_group = null;
        },

        save() {
            const updated = [];
            this.sortables.forEach((s) => {
                s.sortable.toArray().forEach((id, position) => {
                    const ingredient = this.meal.ingredients.find(
                        (i) => i.id === id
                    );
                    updated.push({
                        id: id,
                        position: position + 1,
                        group: s.name,
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