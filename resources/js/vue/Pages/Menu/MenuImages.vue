<template>
    <page v-if="menu">
        <page-header :title="`Menu ${menu.week_number} Images`">
            <button
                class="mr-4 font-semibold text-gray-600 hover:text-blue-600 text-sm"
                @click="selectAll"
            >
                Select All
            </button>
            <button
                :disabled="selected.length === 0"
                class="btn btn-main"
                @click="download"
            >
                {{ button_text }}
            </button>
        </page-header>

        <div class="my-12 grid grid-cols-4 gap-8">
            <div
                v-for="meal in menu.meals"
                :key="meal.id"
                :class="{ 'border-4 border-blue-600': isSelected(meal.id) }"
            >
                <img
                    @click="toggleSelected(meal.id)"
                    :src="meal.title_image.thumb"
                />
            </div>
        </div>
        <form method="POST" ref="fetchForm" action="/admin/api/fetch-images">
            <input type="hidden" name="_token" :value="csrf_token" />
            <input
                v-for="id in selected"
                :key="id"
                type="hidden"
                name="meal_ids[]"
                :value="id"
            />
        </form>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page";
import PageHeader from "../../Components/PageHeader";
import { downloadMealImages } from "../../../apis/images";

export default {
    components: { Page, PageHeader },

    data() {
        return {
            selected: [],
        };
    },

    computed: {
        menu() {
            return this.$store.getters["menus/byId"](this.$route.params.menu);
        },

        csrf_token() {
            return document.getElementById("csrf-token-meta").content;
        },

        button_text() {
            const count =
                this.selected.length === 1
                    ? "image"
                    : `${this.selected.length} images`;
            return `Download ${count}`;
        },
    },

    mounted() {
        this.$store.dispatch("menus/fetchMenus");
    },

    methods: {
        isSelected(id) {
            return this.selected.includes(id);
        },

        toggleSelected(id) {
            if (this.isSelected(id)) {
                return (this.selected = this.selected.filter((i) => i !== id));
            }
            this.selected.push(id);
        },

        selectAll() {
            this.selected = this.menu.meals.map((menu) => menu.id);
        },

        download() {
            // return downloadMealImages(this.selected).then((blob) => {
            //     const link = document.createElement("a");
            //     link.href = window.URL.createObjectURL(new Blob([blob]));
            //     // link.href = URL.createObjectURL(blob);
            //     link.download = "menu_images.zip";
            //     link.click();
            // });

            this.$refs.fetchForm.submit();
        },
    },
};
</script>
