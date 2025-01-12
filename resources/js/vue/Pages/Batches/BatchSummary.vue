<template>
    <div v-if="batch">
        <div class="mb-12 p-6 shadow">
            <colour-label colour="gray" text="Delivery date"></colour-label>
            <p class="mt-6 text-6xl text-gray-700 font-black">
                {{ batch.delivery_date }}
            </p>
        </div>
        <div class="flex justify-between">
            <div>
                <p class="text-center text-5xl font-black">
                    {{ batch.total_kits }}
                </p>
                <colour-label
                    colour="green"
                    text="Kits in this batch"
                ></colour-label>
            </div>
            <div>
                <p class="text-center text-5xl font-black">
                    {{ batch.total_meals }}
                </p>
                <colour-label
                    colour="orange"
                    text="Meals to Pack"
                ></colour-label>
            </div>
            <div>
                <p class="text-center text-5xl font-black">
                    {{ batch.add_ons.reduce((sum, addOn) => sum + addOn.qty, 0) }}
                </p>
                <colour-label
                    colour="indigo"
                    text="Add-Ons to add on"
                ></colour-label>
            </div>
            <div>
                <p class="text-center text-5xl font-black">
                    {{ batch.total_servings }}
                </p>
                <colour-label
                    colour="teal"
                    text="Servings to Deliver"
                ></colour-label>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import ColourLabel from "../../Components/UI/ColourLabel.vue";
export default {
    components: {
        ColourLabel,
    },

    computed: {
        batch() {
            return this.$store.state.menus.current_batch;
        },
    },
};
</script>
