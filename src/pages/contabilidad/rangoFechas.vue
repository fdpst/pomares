<template>
    <VRow
        dense
        align="center">
        <VCol
            cols="12"
            md="4">
            <AppDateTimePicker
                v-model="rango.desde"
                label="Desde"
                prepend-icon="ri-calendar-fill" />
        </VCol>

        <VCol
            cols="12"
            md="4">
            <AppDateTimePicker
                v-model="rango.hasta"
                label="Hasta"
                prepend-icon="ri-calendar-fill" />
        </VCol>

        <VCol
            cols="12"
            md="4">
            <VBtn
                @click="buscarRango"
                rounded="pill"
                color="#5142A6"
                class="text-white mr-2"
                >buscar</VBtn
            >
            <VBtn
                @click="defaultQuery"
                rounded="pill"
                color="secondary"
                class="white--text"
                >reiniciar</VBtn
            >
        </VCol>
    </VRow>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
        },
        modelFactura: {
            type: Object,
            default: {
                tipo: "",
            },
        },
        userId: {
            type: [String, Number],
            default: null,
        },
    },

    data() {
        return {
            desde: false,
            hasta: false,
            rango: {
                desde: moment().startOf("year").format("YYYY-MM-DD"),
                hasta: moment().endOf("year").format("YYYY-MM-DD"),
            },
        };
    },

    methods: {
        buscarRango() {
            if (this.rango.desde == null || this.rango.hasta == null) {
                $toast.warn("Formato de fecha es incorrecto");
                return null;
            }

            // Formatear fechas correctamente (manejar objetos Date o strings)
            let desde = this.rango.desde;
            let hasta = this.rango.hasta;
            
            if (desde instanceof Date) {
                desde = moment(desde).format("YYYY-MM-DD");
            } else if (typeof desde === 'string') {
                desde = moment(desde).format("YYYY-MM-DD");
            }
            
            if (hasta instanceof Date) {
                hasta = moment(hasta).format("YYYY-MM-DD");
            } else if (typeof hasta === 'string') {
                hasta = moment(hasta).format("YYYY-MM-DD");
            }

            // Si la URL contiene get-ingreso-bruto, usar query params
            // Si contiene get-lote-facturas, usar ruta con fechas
            let url = this.url;
            let params = {};
            
            if (this.url.includes('get-ingreso-bruto')) {
                // Para get-ingreso-bruto, las fechas van como query params
                params = {
                    desde: desde,
                    hasta: hasta,
                };
            } else if (this.url.includes('get-lote-facturas')) {
                // Para get-lote-facturas, las fechas van en la ruta
                const tipo = encodeURIComponent(this.modelFactura.tipo || '');
                if (this.userId) {
                    url = `${this.url}/${this.userId}/${desde}/${hasta}/${tipo}`;
                } else {
                    url = `${this.url}/${desde}/${hasta}/${tipo}`;
                }
            } else {
                // Por defecto, intentar en la ruta
                const tipo = encodeURIComponent(this.modelFactura.tipo || '');
                url = `${this.url}/${desde}/${hasta}/${tipo}`;
            }

            axios
                .get(url, {
                    params: params,
                })
                .then((res) => {
                    this.$emit("success_query", res.data);
                })
                .catch((error) => {
                    console.error("Error obteniendo registros:", error);
                    // Solo mostrar error si realmente hay un problema HTTP
                    if (error.response && error.response.status >= 400) {
                        $toast.error("Error obteniendo registros");
                    }
                });
        },

        defaultQuery() {
            this.$emit("default_query");
        },
    },
};
</script>
