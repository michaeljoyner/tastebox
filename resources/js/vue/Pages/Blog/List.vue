<template>
    <page>
        <page-header title="The Tastebox Blog">
            <submit-button :waiting="creating"
                           @click="createPost"
                           mode="dark"
            >New Post
            </submit-button
            >
        </page-header>

        <div class="my-12 shadow max-w-3xl mx-auto">
            <div
                v-for="post in posts"
                :key="post.id"
                class="px-4 py-1 mb-2 border-b border-gray-200"
            >

                    <div class="flex justify-between items-center">
                        <div class="">
                            <router-link :to="`/blog/posts/${post.id}/edit`">
                            <p class="text-lg font-semibold">
                                {{ post.title }}
                            </p>
                            </router-link>

                            <div class="flex gap-2 mt-2 text-xs items-center">
                                <p
                                    v-if="!post.is_public"
                                    class="text-gray-600"
                                >
                                    Created: {{ post.first_created }}
                                </p>
                                <p v-else
                                   class="text-gray-600">
                                    Published: {{ post.first_published }}
                                </p>
                                <ClickToCopy v-if="post.is_public" class="text-xs text-gray-500 hover:text-blue-500" text="Copy Link" :copy-text="`https://tastebox.co.za/blog/${post.slug}`" />

                            </div>
                        </div>
                        <colour-label
                            :colour="post.is_public ? 'green' : 'red'"
                            :text="post.is_public ? 'published' : 'draft'"
                        ></colour-label>
                    </div>

            </div>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import {useStore} from "vuex";
import {computed, onMounted, ref} from "vue";
import {showError} from "../../../libs/notifications.js";
import SubmitButton from "../../Components/UI/SubmitButton.vue";
import {useRouter} from "vue-router";
import ColourLabel from "../../Components/UI/ColourLabel.vue";
import ClickToCopy from "../../Components/UI/ClickToCopy.vue";

export default {
    components: {ClickToCopy, ColourLabel, SubmitButton, PageHeader, Page},

    setup() {
        const store = useStore();
        const router = useRouter();

        const posts = computed(() => store.state.blog.all);

        onMounted(() => {
            store.dispatch("blog/fetch");
        });

        const today = new Date().toLocaleDateString();
        const creating = ref(false);
        const createPost = () => {
            creating.value = true;
            store
                .dispatch("blog/create", {title: `Untitled Post [${today}]`})
                .then((post) => {
                    console.log(post);
                    router.push(`/blog/posts/${post.id}/edit`);
                })
                .catch(() => showError("Failed to create new post"))
                .then(() => (creating.value = false));
        };

        return {posts, creating, createPost};
    },
};
</script>
