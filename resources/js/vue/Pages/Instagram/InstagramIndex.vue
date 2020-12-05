<template>
    <page>
        <page-header title="Influencer HQ">
            <a :href="auth_url" class="btn-main btn">Get Auth</a>
        </page-header>

        <div class="my-12">
            <div class="grid grid-cols-4">
                <div v-for="image in feed">
                    <img :src="image.url" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
export default {
    components: { Page, PageHeader },

    computed: {
        feed() {
            return this.$store.state.instagram.feed.filter(
                (m) => m.type === "image"
            );
        },

        auth_url() {
            return this.$store.state.instagram.auth_url;
        },
    },

    mounted() {
        this.$store.dispatch("instagram/fetch");
    },
};
</script>
