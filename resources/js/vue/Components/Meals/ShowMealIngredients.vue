<template>
    <div>
        <sub-header title="Meal Ingredients">
            <router-link
                :to="`/meals/${meal.id}/manage/ingredients/edit`"
                class="btn btn-main"
                >Edit</router-link
            >
        </sub-header>
        <div class="my-12 shadow p-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-300">
                        <th class="p-2 text-left">Qty</th>
                        <th class="p-2 text-left">Ingredient</th>
                        <th class="p-2 text-left whitespace-no-wrap">
                            In Kit
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr
                        v-for="(ingredient, index) in meal.ingredients"
                        :key="ingredient.id"
                        :class="{ 'bg-gray-100': index % 2 === 1 }"
                    >
                        <td>{{ ingredient.quantity }}</td>
                        <td class="p-2">{{ ingredient.description }}</td>
                        <td><span v-if="ingredient.in_kit">&check;</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../UI/SubHeader";
export default {
    components: { SubHeader },

    props: ["meal"],
};
</script>
