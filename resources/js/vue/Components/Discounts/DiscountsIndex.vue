<template>
    <page>
        <page-header title="Discount Codes">
            <router-link to="/discount-codes/create" class="btn btn-main"
                >Add New Code</router-link
            >
        </page-header>

        <div class="my-12">
            <div
                v-for="code in codes"
                :key="code.id"
                class="my-6 max-w-lg shadow rounded-lg p-6"
            >
                <p class="text-xl font-bold mb-2">
                    <router-link
                        :to="`/discount-codes/${code.id}/edit`"
                        class="hover:text-blue-600"
                    >
                        {{ code.code }}
                    </router-link>
                </p>
                <colour-label
                    :text="code.valid_dates"
                    :colour="code.is_valid ? 'green' : 'orange'"
                ></colour-label>
                <p class="text-4xl mt-3">
                    {{ code.value_string }}
                    <span class="text-gray-500 font-black uppercase">off</span>
                </p>
                <p class="my-2 text-gray-600">
                    {{ code.uses }} uses remaining.
                </p>
            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../PageHeader";
import ColourLabel from "../UI/ColourLabel";
export default {
    components: { ColourLabel, Page, PageHeader },

    computed: {
        codes() {
            return this.$store.state.discounts.all;
        },
    },

    mounted() {
        this.$store.dispatch("discounts/fetch");
    },
};
</script>
