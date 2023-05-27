<template>
    <page>
        <page-header title="Week by Week Report"></page-header>
        <div>
            <canvas
                width="400"
                height="400"
                ref="chartCanvas"
                style="max-height: 500px"
            ></canvas>
        </div>
    </page>
</template>

<script type="text/babel">
import Page from "../../Components/UI/Page.vue";
import PageHeader from "../../Components/PageHeader.vue";
import { Chart, registerables } from "chart.js";
import { onMounted, ref } from "vue";
import { useStore } from "vuex";
import { showError } from "../../../libs/notifications.js";
export default {
    components: { PageHeader, Page },

    setup() {
        Chart.register(...registerables);
        const chartCanvas = ref(null);

        const store = useStore();

        onMounted(() => {
            store
                .dispatch("reports/getWeeklyBatchData")
                .then(() => {
                    var myChart = new Chart(chartCanvas.value, {
                        type: "line",
                        data: {
                            labels: store.getters[
                                "reports/weekly_batch_labels"
                            ],
                            datasets: [
                                {
                                    label: "# Kits",
                                    data: store.getters[
                                        "reports/weekly_batch_kits"
                                    ],
                                    backgroundColor: [
                                        "rgba(255, 99, 132, 0.2)",
                                    ],
                                    borderColor: ["rgba(255, 99, 132, 1)"],
                                    fill: true,
                                    borderWidth: 1,
                                },
                                {
                                    label: "# Meals",
                                    data: store.getters[
                                        "reports/weekly_batch_meals"
                                    ],
                                    backgroundColor: [
                                        "rgba(54, 162, 235, 0.2)",
                                    ],
                                    borderColor: ["rgba(54, 162, 235, 1)"],
                                    fill: true,
                                    borderWidth: 1,
                                },
                                {
                                    label: "# Servings",
                                    data: store.getters[
                                        "reports/weekly_batch_servings"
                                    ],
                                    backgroundColor: [
                                        "rgba(255, 206, 86, 0.2)",
                                    ],
                                    borderColor: ["rgba(255, 206, 86, 1)"],
                                    fill: true,
                                    borderWidth: 1,
                                },
                            ],
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                },
                            },
                        },
                    });
                })
                .catch(() => showError("Failed to load data"));
        });

        return { chartCanvas };
    },
};
</script>
