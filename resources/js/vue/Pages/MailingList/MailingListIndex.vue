<template>
    <page>
        <page-header title="Mailing List"></page-header>

        <div class="my-12">
            <table class="">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Name</th>
                        <th class="p-2">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(contact, index) in list"
                        :key="contact.email"
                        :class="{ 'bg-blue-100': index % 2 === 1 }"
                    >
                        <td class="text-sm px-2 py-1">{{ contact.name }}</td>
                        <td class="text-sm px-2 py-1">{{ contact.email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
export default {
    components: { Page, PageHeader },

    computed: {
        list() {
            return this.$store.state.mailinglist.all;
        },
    },

    mounted() {
        this.$store.dispatch("mailinglist/fetch");
    },
};
</script>
