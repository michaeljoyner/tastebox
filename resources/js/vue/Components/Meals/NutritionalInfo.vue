<template>
    <div>
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 pl-2 text-left">Per serving</th>
                    <th class="pr-2 text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-1 pl-2">Energy (cal)</td>
                    <td class="text-right pr-2">{{ meal.serving_energy }}</td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="py-1 pl-2">Carbohydrates (g)</td>
                    <td class="text-right pr-2">{{ meal.serving_carbs }}</td>
                </tr>
                <tr>
                    <td class="py-1 pl-2">Protein (g)</td>
                    <td class="text-right pr-2">{{ meal.serving_protein }}</td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="py-1 pl-2">Fat (g)</td>
                    <td class="text-right pr-2">{{ meal.serving_fat }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["meal"],
};
</script>
