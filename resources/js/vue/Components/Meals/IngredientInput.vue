<template>
    <div class="relative">
        <input
            ref="input"
            type="text"
            v-model="current"
            class="form-input"
            @keydown.down="moveDown"
            @keydown.up.prevent="moveUp"
            @keydown.enter="useSuggestion"
        />
        <div
            v-show="suggestions.length > 0"
            class="absolute top-100 left-0 right-0 bg-white shadow mt-1"
        >
            <div
                v-for="(suggestion, index) in suggestions"
                class="p-2 hover:bg-gray-100 cursor-pointer"
                :class="{ 'bg-blue-200': suggestion_index === index }"
                @click="chooseSelection(index)"
            >
                {{ suggestion.description }}
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
export default {
    props: ["options"],

    data() {
        return {
            current: "",
            suggestion_index: null,
        };
    },

    computed: {
        suggestions() {
            const search = this.current.toLowerCase();

            if (this.current.length < 3) {
                return [];
            }
            return this.options.filter(
                (option) =>
                    option.description.toLowerCase().includes(search) &&
                    option.description.toLowerCase() !== search
            );
        },
    },

    methods: {
        useSuggestion() {
            if (this.suggestion_index === null) {
                return this.onEntered({ id: null, description: this.current });
            }
            this.onEntered(this.suggestions[this.suggestion_index]);
        },

        chooseSelection(index) {
            this.onEntered(this.suggestions[index]);
        },

        onEntered(ingredient) {
            this.$emit("entered", ingredient);
            this.current = "";
            this.suggestion_index = null;
            this.$refs.input.focus();
        },

        moveDown() {
            if (this.suggestion_index === null) {
                return (this.suggestion_index = 0);
            }

            if (this.suggestion_index >= this.suggestions.length - 1) {
                return;
            }

            this.suggestion_index++;
        },

        moveUp() {
            if (this.suggestion_index === null) {
                this.suggestion_index = this.suggestions.length - 1;
            }

            if (this.suggestion_index === 0) {
                return;
            }

            this.suggestion_index--;
        },
    },
};
</script>
