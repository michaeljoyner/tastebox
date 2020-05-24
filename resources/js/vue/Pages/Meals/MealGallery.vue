<template>
    <div class="max-w-4xl mx-auto" v-if="meal">
        <page-header :title="`Pics: ${meal.name}`"></page-header>

        <sortable-gallery
            :upload-path="`/admin/api/meals/${meal.id}/images`"
            @new-image="addImage"
            :stored-images="images"
            @reordered="setOrder"
            :image-delete-url="getImageDeleteUrl"
            @image-removed="fetchMeal"
        ></sortable-gallery>
    </div>
</template>

<script type="text/babel">
import PageHeader from "../../Components/PageHeader";
import SortableGallery from "../../Components/SortableGallery";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        PageHeader,
        SortableGallery,
    },

    data() {
        return {
            meal: null,
            images: [],
        };
    },

    computed: {
        getImageDeleteUrl() {
            const base = `/admin/api/meals/${this.meal.id}/images/`;
            return (image) => `${base}${image.id}`;
        },
    },

    mounted() {
        this.fetchMeal();
    },

    watch: {
        $route() {
            this.meal = null;
            this.fetchMeal();
        },
    },

    methods: {
        fetchMeal() {
            this.$store
                .dispatch("meals/findById", this.$route.params.id)
                .then((meal) => {
                    this.meal = meal;
                    this.images = meal.gallery;
                })
                .catch(showError);
        },

        addImage(image_data) {
            this.images.push(image_data);
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
