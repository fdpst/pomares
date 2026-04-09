<template>
    <VCard title="Estadísticas">
        <VDivider></VDivider>

        <VContainer>
            <VRow class="mt-5" dense>
                <VCol cols="12">
                    <rango-fechas
                        :url="url"
                        @success_query="setIngresoBruto"
                        ref="rangoFechas"
                    >
                    </rango-fechas>
                </VCol>

                <div v-show="show_chips" class="text-center mt-4">
                    <VChip class="ma-2" color="green" text-color="white">
                        Ingresos:
                        {{ formatPrice(ingreso_bruto.ingreso) }}€
                    </VChip>

                    <VChip class="ma-2" color="red" text-color="white">
                        Gasto:
                        {{ formatPrice(ingreso_bruto.gasto) }}€
                    </VChip>

                    <VChip class="ma-2" color="blue" text-color="white">
                        Total: {{ formatPrice(total) }}€
                    </VChip>

                    <VChip class="ma-2" color="orange" text-color="white">
                        Deuda:
                        {{ formatPrice(ingreso_bruto.suma_deuda) }}€
                    </VChip>
                </div>
            </VRow>

            <VRow
                style="
                    margin: 0 auto;
                    background-color: white;
                    border-radius: 30px;
                "
            >
                <VCol>
                    <div id="chart" class="ml-4">
                        <apexchart
                            height="230"
                            width="99%"
                            type="line"
                            :options="chartOptions"
                            :series="formattedData"
                        />
                    </div>
                </VCol>
            </VRow>

            <VRow>
                <VCol cols="12" md="3">
                    <VCard color="success" to="lista-ingresos" dark>
                        <VCardTitle>
                            <VIcon large left color="white">
                                ri-arrow-right-up-line
                            </VIcon>
                            <span class="title font-weight-light text-white"
                                >Ingresos</span
                            >
                        </VCardTitle>
                    </VCard>
                </VCol>

                <VCol cols="12" md="3">
                    <VCard color="error" to="lista-gastos" dark>
                        <VCardTitle>
                            <VIcon large left color="white">
                                ri-arrow-left-down-line
                            </VIcon>
                            <span class="title font-weight-light text-white"
                                >Gastos</span
                            >
                        </VCardTitle>
                    </VCard>
                </VCol>
            </VRow>

            <loader v-if="isloading"></loader>
        </VContainer>
    </VCard>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import rangoFechas from "./rangoFechas.vue";

export default {
    components: {
        rangoFechas,
        apexchart: VueApexCharts,
    },

    props: {
        chartData: { type: Array, default: () => [] },
        animations: { type: Boolean, default: true },
    },

    data() {
        return {
            estadisticas: [],
            graph_labels: [],
            show_chips: true,
            url: `api/get-ingreso-bruto/${localStorage.getItem("user_id")}`,
            ingreso_bruto: {
                gasto: 0,
                ingreso: 0,
                suma_deuda: 0,
                gasto_desglosado: [],
                user_id: localStorage.getItem("user_id"),
            },
        };
    },

    mounted() {
        this.$refs.rangoFechas.buscarRango();
    },

    methods: {
        setIngresoBruto(data) {
            this.ingreso_bruto = data;
            this.estadisticas = data.estadisticas;
            this.graph_labels = [];
            for (let index = 0; index < this.estadisticas.length; index++) {
                const element = this.estadisticas[index].Mes;
                this.graph_labels.push(element);
            }
        },

        formatCurrency(value) {
            return `${parseFloat(value).toFixed(2)}`;
        },
    },

    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },

        total() {
            let suma = this.ingreso_bruto.ingreso - this.ingreso_bruto.gasto;
            return parseFloat(suma).toFixed(2);
        },

        chartOptions() {
            return {
                chart: {
                    animations: { enabled: this.animations },
                    zoom: { autoScaleYaxis: false },
                },
                xaxis: {
                    categories: this.graph_labels,
                },
                yaxis: {
                    tooltip: { enabled: false },
                    labels: { formatter: (val) => val },
                },
                colors: ["#008f39", "#f44336", "#ff8000", "#0000ff"],
                stroke: { width: [4, 4, 4, 1], curve: "smooth" },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        columnWidth: "60%",
                        borderColor: "#20a020",
                    },
                },
                fill: { opacity: [1, 1, 1, 1] },
            };
        },

        formattedData() {
            const stats = Array.isArray(this.estadisticas) ? this.estadisticas : [];
            const totales = stats.map((d) => d.Totales);
            const totalGastos = stats.map((d) => d.Gastos);
            const totalIngresos = stats.map((d) => d.Ingresos);
            const deudas = stats.map((d) => d.Deudas);
            return [
                { name: "Ingresos", type: "line", data: totalIngresos },
                { name: "Gastos", type: "line", data: totalGastos },
                { name: "Deuda", type: "line", data: deudas },
                { name: "Total", type: "bar", data: totales },
            ];
        },
    },
};
</script>
