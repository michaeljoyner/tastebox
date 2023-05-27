<template>
    <div v-if="post">
        <post-editor :post="post"></post-editor>
    </div>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PostEditor from "../../Components/Blog/PostEditor.vue";
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import { useRoute } from "vue-router";
export default {
    components: { PostEditor, Page },

    setup() {
        const route = useRoute();
        const store = useStore();

        const post = computed(() =>
            store.getters["blog/byId"](route.params.post)
        );

        onMounted(() => {
            store.dispatch("blog/fetch");
        });

        return { post };
    },
};
</script>
