<template>
    <page v-if="meal">
        <page-header title="Meal Photos">
            <router-link :to="`/meals/${meal.id}`" class="btn"
                >Back to Meal</router-link
            >
        </page-header>

        <p class="my-12 text-2xl font-semibold">{{ meal.name }}</p>

        <sortable-gallery
            :upload-path="`/admin/api/meals/${meal.id}/images`"
            @new-image="addImage"
            :stored-images="images"
            @reordered="setOrder"
            :image-delete-url="getImageDeleteUrl"
            @image-removed="$store.dispatch('meals/refresh')"
        ></sortable-gallery>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import SortableGallery from "../../Components/SortableGallery";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        Page,
        PageHeader,
        SortableGallery,
    },

    data() {
        return {};
    },

    computed: {
        meal() {
            return this.$store.getters["meals/byId"](this.$route.params.id);
        },

        images() {
            return this.meal.gallery;
        },

        getImageDeleteUrl() {
            const base = `/admin/api/meals/${this.meal.id}/images/`;
            return (image) => `${base}${image.id}`;
        },
    },

    mounted() {
        this.$store.dispatch("meals/fetchMeals");
    },

    methods: {
        addImage(image_data) {
            this.images.push(image_data);
            this.$store.dispatch("meals/refresh");
        },

        setOrder(image_ids) {
            this.$store
                .dispatch("meals/saveMealGalleryOrder", {
                    id: this.meal.id,
                    image_ids,
                })
                .catch(showError);
        },
    },
};
</script>
