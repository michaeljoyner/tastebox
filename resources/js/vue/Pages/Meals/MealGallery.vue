<template>
    <div>
        <sub-header :title="`Photos: ${meal.name}`"> </sub-header>

        <sortable-gallery
            :upload-path="`/admin/api/meals/${meal.id}/images`"
            @new-image="addImage"
            :stored-images="images"
            @reordered="setOrder"
            :image-delete-url="getImageDeleteUrl"
            @image-removed="$store.dispatch('meals/refresh')"
        ></sortable-gallery>
    </div>
</template>

<script type="text/babel">
import SubHeader from "../../Components/UI/SubHeader";
import SortableGallery from "../../Components/SortableGallery";
import { showError } from "../../../libs/notifications";

export default {
    components: {
        SortableGallery,
        SubHeader,
    },

    props: ["meal"],

    computed: {
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
