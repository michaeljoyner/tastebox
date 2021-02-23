<template>
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
                v-for="(ingredient, index) in ingredients"
                :key="ingredient.meal_ingredient_id"
                :class="{ 'bg-gray-100': index % 2 === 1 }"
            >
                <td>{{ ingredient.quantity }}</td>
                <td class="p-2">
                    {{ ingredient.description }}
                    <span v-show="ingredient.form"
                        >({{ ingredient.form }})</span
                    >
                </td>
                <td><span v-if="ingredient.in_kit">&check;</span></td>
            </tr>
        </tbody>
    </table>
</template>

<script type="text/babel">
export default {
    props: ["ingredients"],
};
</script>
