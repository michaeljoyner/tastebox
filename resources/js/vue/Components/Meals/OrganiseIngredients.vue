<template>
    <div>
        <sub-header title="Organise Ingredients">
            <button class="btn btn-main" @click="showModal = true">
                Add Group
            </button>
        </sub-header>

        <div v-for="(group, name) in groups" class="my-12 shadow p-6">
            <p class="font-bold">{{ name }}</p>
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
                        type="submit"
                        :disabled="!group_name"
                        class="btn btn-main"
                    >
                        Add Group
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
                const group = ingredient.group || "main";
                if (groups.hasOwnProperty(group)) {
                    groups[group].ingredients.push(ingredient);
                    return groups;
                }
                groups[group] = {
                    ingredients: [ingredient],
                    key: group.replace(" ", "_").toLowerCase(),
                };
                return groups;
            }, {}),
            group_name: "",
            showModal: false,
            sortables: [],
        };
    },

    mounted() {
        this.assignGroupSortables();
    },

    methods: {
        addGroup() {
            this.showModal = false;
            this.groups[this.group_name] = {
                ingredients: [],
                key: this.group_name.replace(" ", "_").toLowerCase(),
            };
            this.group_name = "";
            this.$nextTick().then(this.assignGroupSortables);
        },

        assignGroupSortables() {
            this.sortables.forEach((s) => s.sortable.destroy());
            this.sortables = [];
            Object.keys(this.groups).forEach((group) => {
                this.sortables.push({
                    name: group,
                    sortable: Sortable.create(
                        this.$refs[this.groups[group].key][0],
                        {
                            group: "ingredients",
                            onUpdate: this.onOrderChange,
                            onAdd: this.onOrderChange,
                        }
                    ),
                });
            });
        },

        onOrderChange() {
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
    },
};
</script>
