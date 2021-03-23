<template>
    <div class="p-2 border mb-3">
        <div class="flex items-center mb-1">
            <svg
                class="h-4 fill-current text-green-500 mr-2"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <path
                    d="M8.294 16.998c-.435 0-.847-.203-1.111-.553L3.61 11.724a1.392 1.392 0 0 1 .27-1.951 1.392 1.392 0 0 1 1.953.27l2.351 3.104 5.911-9.492a1.396 1.396 0 0 1 1.921-.445c.653.406.854 1.266.446 1.92L9.478 16.34a1.39 1.39 0 0 1-1.12.656c-.022.002-.042.002-.064.002z"
                />
            </svg>
            <p class="flex-1 font-semibold">{{ ingredient.description }}</p>
        </div>
        <div class="flex items-center">
            <input
                type="text"
                v-model="quantity"
                placeholder="Qty"
                class="form-input w-24 mr-4"
            />
            <input
                type="text"
                v-model="form"
                placeholder="form (sliced, chopped, etc)"
                class="form-input flex-1 mr-4"
            />

            <div class="flex items-center mx-4">
                <label :for="`ing_in_kit_${ingredient.id}`">Incl. </label>
                <input
                    class="ml-2"
                    type="checkbox"
                    v-model="in_kit"
                    :id="`ing_in_kit_${ingredient.id}`"
                />
            </div>
            <button
                class="text-red-300 hover:text-red-500 font-bold"
                @click="remove(ingredient)"
            >
                &times;
            </button>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["ingredient"],

    data() {
        return {
            quantity: this.ingredient.quantity,
            form: this.ingredient.form,
            in_kit: this.ingredient.in_kit,
        };
    },

    watch: {
        quantity(to, from) {
            this.update();
        },

        in_kit(to, from) {
            this.update();
        },

        form(to, from) {
            this.update();
        },
    },

    methods: {
        remove(ingredient) {
            this.$emit("remove", { id: this.ingredient.meal_ingredient_id });
        },

        update() {
            this.$emit("updated", {
                id: this.ingredient.id,
                meal_ingredient_id: this.ingredient.meal_ingredient_id,
                description: this.ingredient.description,
                quantity: this.quantity,
                in_kit: this.in_kit,
                form: this.form,
                group: this.ingredient.group,
            });
        },
    },
};
</script>
