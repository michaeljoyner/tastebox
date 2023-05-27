<template>
    <table class="w-full p-6 shadow-lg">
        <tbody>
            <tr
                v-for="adjustment in adjustments"
                :key="adjustment.id"
                class="border-b border-gray-200"
            >
                <td class="px-2 py-3">
                    <check-icon
                        class="h-5 w-5 text-green-400"
                        v-show="adjustment.is_resolved"
                    ></check-icon>
                    <warning-icon
                        class="h-5 w-5 text-red-400"
                        v-show="!adjustment.is_resolved"
                    ></warning-icon>
                </td>
                <td class="px-2 py-3">
                    <router-link
                        :to="`/adjustments/${adjustment.id}/show`"
                        class="text-sm font-semibold"
                        >{{ adjustment.created_at }}</router-link
                    >
                </td>
                <td class="px-2 py-3">
                    <colour-label
                        :colour="
                            adjustment.value_in_cents >= 0 ? 'green' : 'red'
                        "
                        :text="adjustment.amount"
                    ></colour-label>
                </td>
                <td class="px-2 py-3 hidden md:table-cell">
                    <router-link :to="`/adjustments/${adjustment.id}/show`">{{
                        adjustment.customer_name
                    }}</router-link>
                </td>
                <td class="px-2 py-3 hidden md:table-cell">
                    <span class="text-sm w-64 truncate">{{
                        adjustment.reason
                    }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script type="text/babel">
import ColourLabel from "../UI/ColourLabel.vue";
import CheckIcon from "../Icons/CheckIcon.vue";
import WarningIcon from "../UI/Icons/WarningIcon.vue";
export default {
    props: ["adjustments"],

    components: { ColourLabel, CheckIcon, WarningIcon },
};
</script>
