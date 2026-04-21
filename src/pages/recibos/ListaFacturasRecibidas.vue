<template>
    <VCard
        class="pb-10"
        title="Lista de autofacturas">
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
                    class="text-end">
                    <VBtn
                        rounded
                        depressed
                        color="primary"
                        class="mt-1"
                        :to="'/form-facturas-recibidas'"
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
            :headers="headers"
            :items="facturasRecibidasFiltradas"
            :search="search"
            item-key="id"
            class="elevation-1 mt-2">
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
                    {{ format_precio_autofactura(item.total) }}
                </span>
            </template>
            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="'/form-facturas-recibidas-update/' + item.id"
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
                    title="Duplicar autofactura">
                    mdi mdi-content-duplicate
                </VIcon>

                <VIcon
                    @click="verPdfAutofactura(item)"
                    small
                    class="mr-2"
                    color="primary"
                    title="Ver PDF">
                    ri-file-pdf-line
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
        text="¿Está seguro de que desea crear una nueva autofactura con los datos de la seleccionada?"
        @cancel="modalDuplicar = false"
        @confirm="
            () => {
                $router.push('/form-facturas-recibidas-update/' + item.id);
            }
        " />
</template>

<script>
import {localizePrice} from "@/components/Transformations";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import { format_precio_autofactura } from "@/utils/format_precio.js";
import { itemPasaFiltroFecha } from "@/utils/filtroFechaLista.js";

export default {
    mixins: [gestorClienteMixin],
    data() {
        return {
            modalEliminar: false,
            modalDuplicar: false,
            item: "",
            search: "",
            fechaDesde: null,
            fechaHasta: null,
            facturaRecibidas: [],
            headers: [
                {
                    title: "NRO.FACTURA",
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
        this.getFactRecibidas();
    },
    methods: {
        localizePrice,
        format_precio_autofactura,
        getFactRecibidas() {
            axios
                .get(`api/facturas-recibidas`)
                .then(
                    (res) => {
                        this.facturaRecibidas = res.data.facturaRecibidas;
                        // console.log(this.facturaRecibidas)
                    },
                    (err) => {
                        $toast.error("Error consultando autofacturas");
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
        limpiarFiltroFechas() {
            this.fechaDesde = null;
            this.fechaHasta = null;
        },
        verPdfAutofactura(item) {
            axios
                .get(`api/facturas-recibidas-pdf/${item.id}`, {
                    params: {
                        user_id: this.effectiveUserId,
                        _t: Date.now(),
                    },
                    responseType: "blob",
                })
                .then((response) => {
                    const blob = response.data;
                    if (blob.type === "application/json") {
                        blob.text().then((t) => {
                            try {
                                const j = JSON.parse(t);
                                $toast.error(
                                    j.error || j.message || "Error al generar PDF"
                                );
                            } catch {
                                $toast.error("Error al generar PDF");
                            }
                        });
                        return;
                    }
                    const nro =
                        item.nro_factura && item.nro_factura !== "null"
                            ? String(item.nro_factura).replace(
                                  /[^a-zA-Z0-9_-]+/g,
                                  "_"
                              )
                            : "sin_numero";
                    const url = URL.createObjectURL(blob);
                    const win = window.open(url, "_blank", "noopener,noreferrer");
                    if (!win) {
                        URL.revokeObjectURL(url);
                        $toast.error(
                            "Permita ventanas emergentes para ver el PDF en otra pestaña"
                        );
                        return;
                    }
                    setTimeout(() => URL.revokeObjectURL(url), 120000);
                })
                .catch(() => {
                    $toast.error("Error al generar PDF");
                });
        },
        deleteFac(item) {
            this.modalEliminar = false;
            axios.post(`api/facturas-recibidas-delete/${this.item.id}`).then(
                (res) => {
                    this.getFactRecibidas();
                    $toast.sucs("Autofactura eliminada");
                    this.item = "";
                },
                (err) => {
                    $toast.error("Error eliminando autofactura");
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
            this.getFactRecibidas();
            this.modalDuplicar = false
          },
          (err) => {
            $toast.error("Error consultando servicios");
          }
        );
    }*/
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('ListaFacturasRecibidas: Cliente cambiado, recargando facturas recibidas...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.facturaRecibidas = [];
            this.getFactRecibidas();
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        user_id() {
            return localStorage.getItem("user_id");
        },
        effectiveUserId() {
            const role = parseInt(localStorage.getItem("role"));
            const selectedCliente = localStorage.getItem("selected_cliente_id");
            if (role === 3 && selectedCliente) {
                return selectedCliente;
            }
            return this.user_id;
        },
        facturasRecibidasFiltradas() {
            return this.facturaRecibidas.filter((row) =>
                itemPasaFiltroFecha(row.fecha, this.fechaDesde, this.fechaHasta)
            );
        },
    },
};
</script>
