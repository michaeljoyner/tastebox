<template>
    <div>
        <page-header title="Edit Meal">
            <router-link :to="`/meals/${meal.id}`" class="btn mr-4"
                >Back</router-link
            >
            <button class="btn btn-main" @click="save">Save</button>
        </page-header>
        <div class="flex">
            <div class="w-64 pl-4 pt-12">
                <p
                    class="font-bold text-sm mb-3 text-gray-600 hover:text-blue-600 cursor-pointer"
                    :class="{
                        'text-gray-600': section !== 1,
                        'text-blue-600': section === 1,
                    }"
                    @click="section = 1"
                >
                    Basic Info
                </p>
                <p
                    class="font-bold text-sm mb-3 text-gray-600 hover:text-blue-600 cursor-pointer"
                    :class="{
                        'text-gray-600': section !== 2,
                        'text-blue-600': section === 2,
                    }"
                    @click="section = 2"
                >
                    Nutritional Info
                </p>
                <p
                    class="font-bold text-sm mb-3 text-gray-600 hover:text-blue-600 cursor-pointer"
                    :class="{
                        'text-gray-600': section !== 3,
                        'text-blue-600': section === 3,
                    }"
                    @click="section = 3"
                >
                    Ingredients
                </p>
                <p
                    class="font-bold text-sm mb-3 text-gray-600 hover:text-blue-600 cursor-pointer"
                    :class="{
                        'text-gray-600': section !== 4,
                        'text-blue-600': section === 4,
                    }"
                    @click="section = 4"
                >
                    Instructions
                </p>
            </div>
            <div class="flex-1 pb-20">
                <div class="mx-6 p-6 max-w-lg" v-show="section === 1">
                    <p class="text-xl font-bold mb-6">Basic Info</p>
                    <div
                        class="my-4"
                        :class="{ 'border-b border-red-400': formErrors.name }"
                    >
                        <label class="form-label" for="name">Name</label>
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.name"
                            >{{ formErrors.name }}</span
                        >
                        <input
                            type="text"
                            name="name"
                            v-model="formData.name"
                            class="form-input"
                            id="name"
                        />
                    </div>
                    <div class="my-6">
                        <p class="form-label">Classifications:</p>
                        <div class="flex flex-wrap mt-2">
                            <div
                                v-for="classification in classifications"
                                class="mr-6"
                            >
                                <input
                                    type="checkbox"
                                    v-model="formData.classifications"
                                    :value="classification.id"
                                    :id="`classification_${classification.id}`"
                                />
                                <label
                                    :for="`classification_${classification.id}`"
                                    >{{ classification.name }}</label
                                >
                            </div>
                        </div>
                    </div>
                    <div
                        class="my-4"
                        :class="{
                            'border-b border-red-400': formErrors.description,
                        }"
                    >
                        <label class="form-label" for="description"
                            >Description</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.description"
                            >{{ formErrors.description }}</span
                        >
                        <textarea
                            name="description"
                            v-model="formData.description"
                            class="form-input h-32"
                            id="description"
                        />
                    </div>
                    <div
                        class="my-4"
                        :class="{
                            'border-b border-red-400': formErrors.allergens,
                        }"
                    >
                        <label class="form-label" for="allergens"
                            >Allergens</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.allergens"
                            >{{ formErrors.allergens }}</span
                        >
                        <input
                            type="text"
                            name="allergens"
                            v-model="formData.allergens"
                            class="form-input"
                            id="allergens"
                        />
                    </div>
                    <div class="my-6 flex justify-between">
                        <div
                            class="mr-4"
                            :class="{
                                'border-b border-red-400': formErrors.prep_time,
                            }"
                        >
                            <label class="form-label" for="prep_time"
                                >Prep time (mins)</label
                            >
                            <span
                                class="text-xs text-red-400"
                                v-show="formErrors.prep_time"
                                >{{ formErrors.prep_time }}</span
                            >
                            <input
                                type="text"
                                name="prep_time"
                                v-model="formData.prep_time"
                                class="form-input"
                                id="prep_time"
                            />
                        </div>
                        <div
                            class="ml-4"
                            :class="{
                                'border-b border-red-400': formErrors.cook_time,
                            }"
                        >
                            <label class="form-label" for="cook_time"
                                >Cook time (mins)</label
                            >
                            <span
                                class="text-xs text-red-400"
                                v-show="formErrors.cook_time"
                                >{{ formErrors.cook_time }}</span
                            >
                            <input
                                type="text"
                                name="cook_time"
                                v-model="formData.cook_time"
                                class="form-input"
                                id="cook_time"
                            />
                        </div>
                    </div>
                </div>

                <div v-show="section === 2" class="mx-6 p-6 max-w-lg">
                    <p class="text-xl font-bold mb-6">Nutritional Info</p>
                    <p class="my-6">
                        Enter the nutritional information for a single serving
                        of this meal.
                    </p>
                    <div
                        class="flex items-center max-w-sm"
                        :class="{
                            'border-b border-red-400':
                                formErrors.serving_energy,
                        }"
                    >
                        <label
                            class="form-label w-64 text-right"
                            for="serving_energy"
                            >Energy (cal)</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.serving_energy"
                            >{{ formErrors.serving_energy }}</span
                        >
                        <input
                            type="text"
                            name="serving_energy"
                            v-model="formData.serving_energy"
                            class="form-input ml-2"
                            id="serving_energy"
                        />
                    </div>
                    <div
                        class="flex items-center max-w-sm"
                        :class="{
                            'border-b border-red-400': formErrors.serving_carbs,
                        }"
                    >
                        <label
                            class="form-label w-64 text-right"
                            for="serving_carbs"
                            >Carbs (g)</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.serving_carbs"
                            >{{ formErrors.serving_carbs }}</span
                        >
                        <input
                            type="text"
                            name="serving_carbs"
                            v-model="formData.serving_carbs"
                            class="form-input ml-2"
                            id="serving_carbs"
                        />
                    </div>
                    <div
                        class="flex items-center max-w-sm"
                        :class="{
                            'border-b border-red-400':
                                formErrors.serving_protein,
                        }"
                    >
                        <label
                            class="form-label w-64 text-right"
                            for="serving_protein"
                            >Protein (g)</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.serving_protein"
                            >{{ formErrors.serving_protein }}</span
                        >
                        <input
                            type="text"
                            name="serving_protein"
                            v-model="formData.serving_protein"
                            class="form-input ml-2"
                            id="serving_protein"
                        />
                    </div>
                    <div
                        class="flex items-center max-w-sm"
                        :class="{
                            'border-b border-red-400': formErrors.serving_fat,
                        }"
                    >
                        <label
                            class="form-label w-64 text-right"
                            for="serving_fat"
                            >Fat (g)</label
                        >
                        <span
                            class="text-xs text-red-400"
                            v-show="formErrors.serving_fat"
                            >{{ formErrors.serving_fat }}</span
                        >
                        <input
                            type="text"
                            name="serving_fat"
                            v-model="formData.serving_fat"
                            class="form-input ml-2"
                            id="serving_fat"
                        />
                    </div>
                </div>

                <div v-show="section === 3" class="mx-6 p-6">
                    <p class="text-xl font-bold mb-6">Ingredients</p>
                    <p class="my-6">
                        Enter the ingredients by typing into the input below.
                    </p>
                    <div class="mr-3 w-full">
                        <ingredient-list
                            v-model="formData.ingredients"
                        ></ingredient-list>
                    </div>
                </div>

                <div v-show="section === 4" class="mx-6 p-6">
                    <p class="text-xl font-bold mb-6">Instructions</p>
                    <wysiwyg
                        class="list-disc"
                        v-model="formData.instructions"
                    ></wysiwyg>
                </div>

                <div
                    class="fixed bottom-0 left-0 z-50 bg-white shadow w-full h-16 flex items-center justify-end"
                >
                    <div
                        class="flex items-center justify-between w-full max-w-4xl px-6"
                    >
                        <button
                            class="btn btn-second mx-4"
                            :disabled="section === 1"
                            @click="prevPage"
                        >
                            Prev
                        </button>
                        <button
                            class="btn btn-second mx-4"
                            :disabled="section === 4"
                            @click="nextPage"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../PageHeader";
import IngredientList from "../../Components/Meals/IngredientList";
import Wysiwyg from "@dymantic/vue-trix-editor";
import {
    showSuccess,
    showError,
    showWarning,
} from "../../../libs/notifications";

export default {
    components: {
        PageHeader,
        IngredientList,
        Wysiwyg,
    },

    props: ["meal"],

    data() {
        return {
            ready: false,
            section: 1,
            formData: {
                name: "",
                description: "",
                prep_time: null,
                cook_time: null,
                allergens: "",
                instructions: "",
                serving_energy: "",
                serving_carbs: "",
                serving_fat: "",
                serving_protein: "",
                ingredients: [],
                classifications: [],
            },
            formErrors: {
                name: "",
                description: "",
                prep_time: null,
                cook_time: null,
                allergens: "",
                instructions: "",
                serving_energy: "",
                serving_carbs: "",
                serving_fat: "",
                serving_protein: "",
                ingredients: "",
                classifications: "",
            },
        };
    },

    computed: {
        classifications() {
            return this.$store.state.meals.classifications;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchIngredients").catch(showError);
        this.$store.dispatch("meals/fetchClassifications").catch(showError);
        this.setForm();
    },

    methods: {
        setForm() {
            this.formData = this.meal;
            this.formData.classifications = this.meal.classifications.map(
                (c) => c.id
            );
        },

        prevPage() {
            if (this.section === 1) {
                return;
            }
            this.section--;
        },

        nextPage() {
            if (this.section === 4) {
                return;
            }
            this.section++;
        },

        save() {
            const mealData = { ...this.formData };

            this.$store
                .dispatch("meals/save", { id: this.meal.id, mealData })
                .then(() => showSuccess("meal saved successfully"))
                .catch(this.handleError);
        },

        handleError({ status, data }) {
            if (status === 422) {
                return showWarning("some of your input is invalid");
            }

            showError("failed to save meal");
        },
    },
};
</script>

<style lang="less">
.dd-vue-trix ul {
    list-style-type: disc;
    padding-left: 2rem;
}

.dd-vue-trix ol {
    list-style-type: decimal;
    padding-left: 2rem;
}
</style>
