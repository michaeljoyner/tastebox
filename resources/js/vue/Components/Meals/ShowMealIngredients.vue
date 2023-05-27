<template>
    <div>
        <sub-header title="Meal Ingredients">
            <router-link
                :to="`/meals/${meal.id}/manage/ingredients/organise`"
                class="btn mx-4"
                >Organise</router-link
            >
            <router-link
                :to="`/meals/${meal.id}/manage/ingredients/edit`"
                class="btn btn-main"
                >Edit</router-link
            >
        </sub-header>
        <div class="my-12 shadow p-6" v-for="group in groups">
            <p class="font-semibold capitalize text-lg mb-2">
                {{ group.name }}
            </p>
            <ingredients-table
                :ingredients="group.ingredients"
            ></ingredients-table>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader.vue";
import IngredientsTable from "./IngredientsTable.vue";
export default {
    components: { IngredientsTable, SubHeader },

    props: ["meal"],

    computed: {
        groups() {
            return this.meal.ingredients.reduce((groups, ingredient) => {
                const groupName = ingredient.group || "main";
                const existing = groups.find((g) => g.name === groupName);
                if (existing) {
                    existing.ingredients.push(ingredient);
                    return groups;
                }
                groups.push({
                    ingredients: [ingredient],
                    name: groupName,
                });
                return groups;
            }, []);
        },
    },
};
</script>
