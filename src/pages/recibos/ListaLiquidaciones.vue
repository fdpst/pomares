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
                    cols="12"
                    md="4">
                    <AppDateTimePicker
                        v-model="fechaDesde"
                        label="Fecha desde"
                        prepend-icon="ri-calendar-fill"
                    />
                </VCol>
                <VCol
                    cols="12"
                    md="4">
                    <AppDateTimePicker
                        v-model="fechaHasta"
                        label="Fecha hasta"
                        prepend-icon="ri-calendar-fill"
                    />
                </VCol>
                <VCol
                    cols="12"
                    md="4"
                    class="d-flex align-center pb-2">
                    <VBtn
                        variant="text"
                        color="secondary"
                        size="small"
                        @click="limpiarFiltroFechas">
                        Quitar filtro de fechas
                    </VBtn>
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
            <template v-slot:item.nro_factura="{item}">
                <span v-if="item.nro_factura != null">
                    {{
                        item.nro_factura == null || item.nro_factura == "null"
                            ? "Sin información"
                            : item.nro_factura
                    }}
                </span>
            </template>
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
        text="Se agruparán las liquidaciones por punto de venta (proveedor) y se creará una autofactura por cada uno, con una línea por liquidación que tenga comisión calculable. El Nº de cada autofactura será el siguiente correlativo CO-N (misma serie que las liquidaciones). Las liquidaciones sin proveedor o sin comisión aplicable se omitirán. ¿Continuar?"
        @cancel="modalFacturaComisiones = false"
        @confirm="confirmarFacturaComisiones" />
</template>

<script>
import {localizePrice} from "@/components/Transformations";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import { effectiveBusinessUserId } from "@/utils/tenantContext";
import { itemPasaFiltroFecha } from "@/utils/filtroFechaLista.js";
import {
    borrarFiltroFechasLista,
    escribirFiltroFechasLista,
    leerFiltroFechasLista,
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
            liquidaciones: [],
            headers: [
                {
                    title: "NRO. LIQUIDACIÓN",
                    value: "nro_factura",
                },
                {
                    title: "Fecha",
                    value: "fecha",
                },
                {
                    title: "Punto de venta",
                    value: "proveedor.nombre",
                },
                {
                    title: "Descripción",
                    value: "descripcion",
                },
                {
                    title: "Total",
                    value: "total",
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
        this.restaurarFiltroFechasDesdeStorage();
        this.getLiquidaciones();
    },
    watch: {
        fechaDesde() {
            this.persistirFiltroFechas();
        },
        fechaHasta() {
            this.persistirFiltroFechas();
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
        persistirFiltroFechas() {
            escribirFiltroFechasLista(
                LISTA_PERSIST_ID,
                this.effectiveUserId,
                this.fechaDesde,
                this.fechaHasta
            );
        },
        limpiarFiltroFechas() {
            borrarFiltroFechasLista(LISTA_PERSIST_ID, this.effectiveUserId);
            this.fechaDesde = null;
            this.fechaHasta = null;
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
            this.restaurarFiltroFechasDesdeStorage();
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
        liquidacionesFiltradas() {
            return this.liquidaciones.filter((row) =>
                itemPasaFiltroFecha(row.fecha, this.fechaDesde, this.fechaHasta)
            );
        },
    },
};
</script>
