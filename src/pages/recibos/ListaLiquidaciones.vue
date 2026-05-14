<template>
    <VCard
        class="pb-10"
        title="Lista de liquidaciones">
        <div class="ps-5 pe-5 pb-5">
            <VRow>
                <VCol
                    cols="12"
                    md="8">
                    <VTextField
                        prepend-icon="ri-user-search-fill"
                        v-model="search"
                        label="Búsqueda"></VTextField>
                </VCol>

                <VCol
                    cols="12"
                    md="4"
                    class="text-end d-flex flex-wrap justify-end ga-2">
                    <VBtn
                        rounded
                        depressed
                        color="secondary"
                        class="mt-1"
                        :disabled="selected.length === 0"
                        title="Una autofactura por punto de venta; varias liquidaciones en la misma factura"
                        @click="abrirModalFacturaComisiones">
                        Factura por comisiones
                    </VBtn>
                    <VBtn
                        rounded
                        depressed
                        color="primary"
                        class="mt-1"
                        :to="'/form-liquidaciones'"
                        >Nuevo</VBtn
                    >
                </VCol>
            </VRow>

            <VRow class="mt-2 align-end">
                <VCol
                    cols="6"
                    sm="4"
                    md="2"
                    lg="2">
                    <AppDateTimePicker
                        v-model="fechaDesde"
                        density="compact"
                        hide-details
                        label="Fecha desde"
                        prepend-icon="ri-calendar-fill"
                    />
                </VCol>
                <VCol
                    cols="6"
                    sm="4"
                    md="2"
                    lg="2">
                    <AppDateTimePicker
                        v-model="fechaHasta"
                        density="compact"
                        hide-details
                        label="Fecha hasta"
                        prepend-icon="ri-calendar-fill"
                    />
                </VCol>
                <VCol
                    cols="12"
                    sm="12"
                    md="8"
                    lg="8"
                    class="d-flex align-center flex-wrap ga-2 pb-1 pb-md-0">
                    <VSwitch
                        v-model="mostrarFacturadas"
                        color="primary"
                        density="compact"
                        hide-details
                        inset
                        class="liquidacion-switch-facturadas"
                        title="Mostrar liquidaciones ya facturadas (autofactura comisiones)"
                        label="Mostrar facturadas"
                    />
                    <VBtn
                        variant="tonal"
                        color="secondary"
                        size="small"
                        rounded="lg"
                        @click="limpiarTodosLosFiltros">
                        Eliminar filtros
                    </VBtn>
                </VCol>
            </VRow>

            <VRow
                v-if="resumenCantidadesArticulos.length"
                class="mt-3">
                <VCol cols="12">
                    <div class="resumen-articulos-bloque pa-3 rounded-lg">
                        <div class="text-caption mb-2 resumen-articulos-caption">
                            Total unidades por artículo (según fecha y búsqueda)
                        </div>
                        <div class="d-flex flex-wrap ga-2">
                            <VChip
                                v-for="row in resumenCantidadesArticulos"
                                :key="row.key"
                                size="small"
                                variant="flat"
                                class="resumen-articulo-chip">
                                {{ row.label }}:
                                {{ formatCantidadResumen(row.total) }}
                            </VChip>
                        </div>
                    </div>
                </VCol>
            </VRow>
        </div>

        <loader v-if="isloading"></loader>

        <VDataTable
            v-model="selected"
            :headers="headers"
            :items="liquidacionesFiltradas"
            :search="search"
            item-key="id"
            class="elevation-1 mt-2"
            :show-select="true"
            :return-object="true">
            <template v-slot:item.fecha="{item}">
                <span v-if="item.fecha != null">
                    {{ formatDateEs(item.fecha) }}
                </span>
            </template>
            <template v-slot:item.total="{item}">
                <span v-if="item.total != null">
                    {{ formatPrice(item.total) }}€
                </span>
            </template>
            <template v-slot:item.factura_autofactura_nro="{item}">
                <span
                    v-if="
                        item.factura_recibida &&
                        item.factura_recibida.nro_factura != null &&
                        item.factura_recibida.nro_factura !== 'null'
                    ">
                    {{ item.factura_recibida.nro_factura }}
                </span>
                <span
                    v-else
                    class="text-medium-emphasis"
                    >—</span
                >
            </template>
            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="'/form-liquidaciones-update/' + item.id"
                    class="action-buttons">
                    <VIcon
                        small
                        class="mr-2"
                        color="grey-600">
                        ri-pencil-line
                    </VIcon>
                </RouterLink>

                <VIcon
                    @click="mostrarModalEliminar(item)"
                    small
                    class="mr-2"
                    color="red">
                    ri-delete-bin-line
                </VIcon>

                <VIcon
                    @click="mostrarModalDuplicar(item)"
                    small
                    class="mr-2"
                    color="orange"
                    title="Duplicar liquidación">
                    mdi mdi-content-duplicate
                </VIcon>
            </template>
        </VDataTable>
    </VCard>

    <ConfirmDialog
        v-model="modalEliminar"
        @cancel="closeModal"
        @confirm="deleteFac"
        color="primary" />

    <ConfirmDialog
        v-model="modalDuplicar"
        color="info"
        text="¿Está seguro de que desea crear una nueva liquidación con los datos de la seleccionada?"
        @cancel="modalDuplicar = false"
        @confirm="
            () => {
                $router.push('/form-liquidaciones-update/' + item.id);
            }
        " />

    <ConfirmDialog
        v-model="modalFacturaComisiones"
        color="primary"
        text="Se agruparán las liquidaciones por punto de venta (proveedor) y se creará una autofactura por cada uno, con una línea por liquidación que tenga comisión calculable. El Nº de cada autofactura será CO-n/año-Nº PV según el correlativo de ese punto de venta y año (independiente de las liquidaciones CO-N). Las liquidaciones sin proveedor o sin comisión aplicable se omitirán. ¿Continuar?"
        @cancel="modalFacturaComisiones = false"
        @confirm="confirmarFacturaComisiones" />
</template>

<script>
import {localizePrice} from "@/components/Transformations";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import { effectiveBusinessUserId } from "@/utils/tenantContext";
import { itemPasaFiltroFecha } from "@/utils/filtroFechaLista.js";
import { nroCoToSoloNumero } from "@/utils/nroCoLiquidacion.js";
import {
    borrarFiltroBusquedaLista,
    borrarFiltroFechasLista,
    borrarMostrarFacturadasLista,
    escribirFiltroBusquedaLista,
    escribirFiltroFechasLista,
    escribirMostrarFacturadasLista,
    leerFiltroBusquedaLista,
    leerFiltroFechasLista,
    leerMostrarFacturadasLista,
} from "@/utils/persistenciaFiltroFechaLista.js";

const LISTA_PERSIST_ID = "liquidaciones";

export default {
    mixins: [gestorClienteMixin],
    data() {
        return {
            modalEliminar: false,
            modalDuplicar: false,
            modalFacturaComisiones: false,
            selected: [],
            item: "",
            search: "",
            fechaDesde: null,
            fechaHasta: null,
            mostrarFacturadas: false,
            liquidaciones: [],
            headers: [
                {
                    title: "Fecha",
                    value: "fecha",
                },
                {
                    title: "Punto de venta",
                    value: "proveedor.nombre",
                },
                {
                    title: "Total",
                    value: "total",
                },
                {
                    title: "Facturada",
                    value: "factura_autofactura_nro",
                    sortable: false,
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ],
        };
    },
    created() {
        this.restaurarFiltrosDesdeStorage();
        this.getLiquidaciones();
    },
    activated() {
        this.restaurarFiltrosDesdeStorage();
    },
    watch: {
        fechaDesde() {
            this.persistirFiltroFechas();
        },
        fechaHasta() {
            this.persistirFiltroFechas();
        },
        search() {
            this.persistirBusqueda();
        },
        mostrarFacturadas() {
            this.persistirMostrarFacturadas();
        },
    },
    methods: {
        localizePrice,
        restaurarFiltroFechasDesdeStorage() {
            const { desde, hasta } = leerFiltroFechasLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId
            );
            this.fechaDesde = desde;
            this.fechaHasta = hasta;
        },
        restaurarBusquedaDesdeStorage() {
            this.search = leerFiltroBusquedaLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId
            );
        },
        restaurarMostrarFacturadasDesdeStorage() {
            this.mostrarFacturadas = leerMostrarFacturadasLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId
            );
        },
        restaurarFiltrosDesdeStorage() {
            this.restaurarFiltroFechasDesdeStorage();
            this.restaurarBusquedaDesdeStorage();
            this.restaurarMostrarFacturadasDesdeStorage();
        },
        persistirFiltroFechas() {
            escribirFiltroFechasLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId,
                this.fechaDesde,
                this.fechaHasta
            );
        },
        persistirBusqueda() {
            escribirFiltroBusquedaLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId,
                this.search
            );
        },
        persistirMostrarFacturadas() {
            escribirMostrarFacturadasLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId,
                this.mostrarFacturadas
            );
        },
        limpiarTodosLosFiltros() {
            borrarFiltroFechasLista(LISTA_PERSIST_ID, this.effectiveUserId);
            borrarFiltroBusquedaLista(LISTA_PERSIST_ID, this.effectiveUserId);
            borrarMostrarFacturadasLista(LISTA_PERSIST_ID, this.effectiveUserId);
            this.fechaDesde = null;
            this.fechaHasta = null;
            this.search = "";
            this.mostrarFacturadas = false;
        },
        formatCantidadResumen(val) {
            const n = Number(val);
            if (!Number.isFinite(n)) {
                return "0";
            }
            return new Intl.NumberFormat("es-ES", {
                maximumFractionDigits: 4,
            }).format(n);
        },
        getLiquidaciones() {
            axios
                .get(`api/liquidaciones`)
                .then(
                    (res) => {
                        this.liquidaciones = res.data.liquidaciones;
                    },
                    (err) => {
                        $toast.error("Error consultando liquidaciones");
                    }
                );
        },
        mostrarModalEliminar(item) {
            this.modalEliminar = true;
            this.item = item;
        },
        mostrarModalDuplicar(item) {
            this.modalDuplicar = true;
            this.item = item;
        },
        closeModal() {
            this.modalEliminar = false;
            this.item = "";
        },
        abrirModalFacturaComisiones() {
            if (!this.selected.length) {
                return $toast.error("Seleccione al menos una liquidación");
            }
            this.modalFacturaComisiones = true;
        },
        confirmarFacturaComisiones() {
            this.modalFacturaComisiones = false;
            const ids = this.selected.map((s) => s.id);
            axios
                .post(`api/liquidaciones-factura-comisiones`, {
                    liquidacion_ids: ids,
                    user_id: this.effectiveUserId,
                })
                .then((res) => {
                    const creadas = res.data?.facturas_recibidas || [];
                    const omitidas = res.data?.omitidas || [];
                    if (omitidas.length) {
                        $toast.info(
                            `${omitidas.length} liquidación(es) sin comisión aplicable; no se generó factura para ellas.`
                        );
                    }
                    if (!creadas.length) {
                        this.getLiquidaciones();
                        return;
                    }
                    $toast.sucs(
                        creadas.length === 1
                            ? "Autofactura por comisiones creada"
                            : `${creadas.length} autofacturas creadas (una por punto de venta)`
                    );
                    this.selected = [];
                    if (creadas.length === 1) {
                        this.$router.push(
                            "/form-facturas-recibidas-update/" + creadas[0].id
                        );
                    } else {
                        this.$router.push("/lista-facturas-recibidas");
                    }
                })
                .catch((err) => {
                    const msg =
                        err.response?.data?.error ||
                        "Error al generar la autofactura";
                    $toast.error(msg);
                });
        },
        deleteFac(item) {
            this.modalEliminar = false;
            axios.post(`api/liquidaciones-delete/${this.item.id}`).then(
                (res) => {
                    this.getLiquidaciones();
                    $toast.sucs("Liquidación eliminada");
                    this.item = "";
                },
                (err) => {
                    $toast.error("Error eliminando liquidación");
                }
            );
        },
        /*duplicarFacturaRecibida(){
      console.log('item', this.item)
      axios
        .post(`api/duplicar-factura-recibida`, this.item)
        .then(
          (res) => {
            $toast.sucs("Factura duplicada con exito");
            this.getLiquidaciones();
            this.modalDuplicar = false
          },
          (err) => {
            $toast.error("Error consultando servicios");
          }
        );
    }*/
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('ListaLiquidaciones: Cliente cambiado, recargando liquidaciones...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.liquidaciones = [];
            this.selected = [];
            this.restaurarFiltrosDesdeStorage();
            this.getLiquidaciones();
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        effectiveUserId() {
            return effectiveBusinessUserId();
        },
        liquidacionesPorFecha() {
            return this.liquidaciones.filter((row) =>
                itemPasaFiltroFecha(row.fecha, this.fechaDesde, this.fechaHasta)
            );
        },
        liquidacionesFiltradas() {
            const rows = this.liquidacionesPorFecha;
            if (this.mostrarFacturadas) {
                return rows;
            }
            return rows.filter((row) => {
                const fid = row.factura_recibida_id;
                return (
                    fid == null ||
                    fid === "" ||
                    fid === 0 ||
                    fid === "0"
                );
            });
        },
        /** Mismas liquidaciones que vería la tabla con fecha, facturación, búsqueda. */
        liquidacionesParaResumenTotales() {
            const q = (this.search || "").trim().toLowerCase();
            const visibles = this.liquidacionesFiltradas;
            if (!q) {
                return visibles;
            }
            return visibles.filter((liq) => {
                const parts = [
                    liq.nro_factura,
                    nroCoToSoloNumero(liq.nro_factura),
                    liq.descripcion,
                    liq.total != null ? String(liq.total) : "",
                    liq.fecha != null ? String(liq.fecha) : "",
                    liq.proveedor?.nombre,
                    liq.factura_recibida?.nro_factura,
                ];
                const items = Array.isArray(liq.items) ? liq.items : [];
                for (const it of items) {
                    parts.push(it.concepto);
                    parts.push(it.servicio?.descripcion);
                }
                const hay = parts
                    .filter((p) => p != null && String(p).trim() !== "")
                    .join(" ")
                    .toLowerCase();
                return hay.includes(q);
            });
        },
        resumenCantidadesArticulos() {
            const map = new Map();
            for (const liq of this.liquidacionesParaResumenTotales) {
                const items = Array.isArray(liq.items) ? liq.items : [];
                for (const it of items) {
                    const sid = parseInt(String(it.id_servicio ?? 0), 10) || 0;
                    const descServ =
                        it.servicio && it.servicio.descripcion != null
                            ? String(it.servicio.descripcion).trim()
                            : "";
                    const label =
                        sid > 0 && descServ
                            ? descServ
                            : String(it.concepto || "Artículo").trim() ||
                              "Artículo";
                    const key =
                        sid > 0 ? `s:${sid}` : `c:${label.toLowerCase()}`;
                    const cant = parseFloat(String(it.cantidad ?? 0)) || 0;
                    const prev = map.get(key);
                    if (prev) {
                        prev.total += cant;
                    } else {
                        map.set(key, { key, label, total: cant });
                    }
                }
            }
            return [...map.values()].sort((a, b) =>
                a.label.localeCompare(b.label, "es", { sensitivity: "base" })
            );
        },
    },
};
</script>

<style scoped>
.resumen-articulos-bloque {
    background-color: #eeeeee;
    border: 1px solid rgba(0, 0, 0, 0.08);
}
.resumen-articulos-caption {
    color: #000;
}
.resumen-articulo-chip {
    background-color: #e0e0e0 !important;
}
.resumen-articulos-bloque :deep(.resumen-articulo-chip .v-chip__content) {
    color: #000 !important;
}
.liquidacion-switch-facturadas :deep(.v-label) {
    white-space: normal;
    line-height: 1.25;
    font-size: 0.8125rem;
}
</style>
