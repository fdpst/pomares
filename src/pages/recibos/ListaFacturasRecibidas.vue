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
                    class="text-end d-flex flex-wrap justify-end ga-2">
                    <VBtn
                        rounded
                        depressed
                        color="secondary"
                        class="mt-1"
                        :disabled="selected.length === 0 || generandoRemesa"
                        :loading="generandoRemesa"
                        title="Genera fichero XML SEPA para el banco"
                        @click="generarRemesa">
                        Generar remesa
                    </VBtn>
                    <VBtn
                        rounded
                        depressed
                        color="teal-darken-2"
                        class="mt-1"
                        :disabled="!puedeCambiarFechaLiquidaciones || guardandoFechaLiquidaciones"
                        :loading="guardandoFechaLiquidaciones"
                        title="Cambia la fecha que aparece en el PDF de resumen de liquidación"
                        @click="abrirDialogFechaLiquidaciones">
                        Cambiar fecha liquidaciones
                    </VBtn>
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
            v-model="selected"
            :headers="headers"
            :items="facturasRecibidasFiltradas"
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
                    {{ format_precio_autofactura(item.total) }}
                </span>
            </template>
            <template v-slot:item.contabilizado="{item}">
                <VCheckbox
                    density="compact"
                    hide-details
                    class="mt-0"
                    :model-value="contabilizadoBool(item)"
                    @update:model-value="(v) => setContabilizado(item, v)" />
            </template>
            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="'/form-facturas-recibidas-update/' + item.id"
                    class="action-buttons">
                    <VIcon
                        small
                        class="mr-2">
                        ri-pencil-line
                    </VIcon>
                </RouterLink>

                <VIcon
                    @click="mostrarModalEliminar(item)"
                    small
                    class="mr-2"
                    color="error">
                    ri-delete-bin-line
                </VIcon>

                <VIcon
                    @click="verPdfAutofactura(item)"
                    small
                    class="mr-2"
                    color="info"
                    title="Ver PDF">
                    ri-file-pdf-line
                </VIcon>

                <VIcon
                    v-if="item.resumen_liquidacion"
                    @click="verPdfResumenLiquidacion(item)"
                    small
                    class="mr-2"
                    color="teal-darken-2"
                    title="Resumen de liquidación (PDF)">
                    ri-file-list-3-line
                </VIcon>
            </template>
        </VDataTable>
    </VCard>

    <ConfirmDialog
        v-model="modalEliminar"
        @cancel="closeModal"
        @confirm="deleteFac"
        color="primary" />

    <VDialog
        v-model="dialogFechaLiquidaciones"
        max-width="440"
        persistent>
        <VCard>
            <VCardTitle class="text-h6 pa-4">
                Cambiar fecha liquidaciones
            </VCardTitle>
            <VCardText class="pt-2 pb-0">
                <p class="text-body-2 mb-4">
                    Esta fecha aparecerá en la cabecera del PDF de resumen de
                    liquidación de las autofacturas seleccionadas
                    ({{ selected.length }}).
                </p>
                <AppDateTimePicker
                    v-model="fechaLiquidaciones"
                    label="Fecha en el resumen"
                    prepend-icon="ri-calendar-fill"
                />
            </VCardText>
            <VCardActions class="pa-4">
                <VSpacer />
                <VBtn
                    variant="text"
                    color="secondary"
                    :disabled="guardandoFechaLiquidaciones"
                    @click="cerrarDialogFechaLiquidaciones">
                    Cancelar
                </VBtn>
                <VBtn
                    class="btn-guardar-fecha-liquidaciones"
                    color="#DCFF2E"
                    :loading="guardandoFechaLiquidaciones"
                    :disabled="!fechaLiquidaciones"
                    @click="guardarFechaLiquidaciones">
                    Guardar
                </VBtn>
            </VCardActions>
        </VCard>
    </VDialog>

</template>

<script>
import {localizePrice} from "@/components/Transformations";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import { effectiveBusinessUserId } from "@/utils/tenantContext";
import { abrirPdfEnNuevaPestana } from "@/utils/pdfOpen";
import { format_precio_autofactura } from "@/utils/format_precio.js";
import { itemPasaFiltroFecha } from "@/utils/filtroFechaLista.js";
import {
    borrarFiltroFechasLista,
    escribirFiltroFechasLista,
    leerFiltroFechasLista,
} from "@/utils/persistenciaFiltroFechaLista.js";

const LISTA_PERSIST_ID = "facturas-recibidas";

export default {
    mixins: [gestorClienteMixin],
    data() {
        return {
            modalEliminar: false,
            item: "",
            selected: [],
            generandoRemesa: false,
            dialogFechaLiquidaciones: false,
            fechaLiquidaciones: null,
            guardandoFechaLiquidaciones: false,
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
                    title: "Contabilizado",
                    value: "contabilizado",
                    sortable: true,
                    width: "9rem",
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
        this.getFactRecibidas();
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
        format_precio_autofactura,
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
        closeModal() {
            this.modalEliminar = false;
            this.item = "";
        },
        limpiarFiltroFechas() {
            borrarFiltroFechasLista(LISTA_PERSIST_ID, this.effectiveUserId);
            this.fechaDesde = null;
            this.fechaHasta = null;
        },
        contabilizadoBool(item) {
            const c = item?.contabilizado;
            return (
                c === true ||
                c === 1 ||
                c === "1" ||
                c === "true"
            );
        },
        setContabilizado(item, value) {
            const prev = this.contabilizadoBool(item);
            const next = !!value;
            item.contabilizado = next;
            const payload = {
                contabilizado: next,
                user_id: this.effectiveUserId,
            };
            const role = parseInt(localStorage.getItem("role"), 10);
            const selectedCliente = localStorage.getItem(
                "selected_cliente_id"
            );
            if (role === 3 && selectedCliente) {
                payload.cliente_id = selectedCliente;
            }
            axios
                .post(
                    `api/facturas-recibidas-contabilizado/${item.id}`,
                    payload
                )
                .then((res) => {
                    const fr = res.data?.facturaRecibida;
                    if (fr) {
                        item.contabilizado = !!fr.contabilizado;
                    }
                })
                .catch(() => {
                    item.contabilizado = prev;
                    $toast.error("No se pudo actualizar contabilizado");
                });
        },
        abrirDialogFechaLiquidaciones() {
            if (!this.puedeCambiarFechaLiquidaciones) {
                return;
            }
            const conResumen = this.selected.filter((row) => row.resumen_liquidacion);
            const ref = conResumen[0] || this.selected[0];
            this.fechaLiquidaciones =
                ref?.fecha_resumen_liquidacion || ref?.fecha || null;
            this.dialogFechaLiquidaciones = true;
        },
        cerrarDialogFechaLiquidaciones() {
            this.dialogFechaLiquidaciones = false;
            this.fechaLiquidaciones = null;
        },
        guardarFechaLiquidaciones() {
            if (!this.fechaLiquidaciones || this.selected.length === 0) {
                return;
            }

            const ids = this.selected
                .filter((row) => row.resumen_liquidacion)
                .map((row) => row.id);

            if (ids.length === 0) {
                $toast.error(
                    "Ninguna autofactura seleccionada tiene resumen de liquidación"
                );
                return;
            }

            const payload = {
                ids,
                fecha: this.fechaLiquidaciones,
                user_id: this.effectiveUserId,
            };
            const role = parseInt(localStorage.getItem("role"), 10);
            const selectedCliente = localStorage.getItem("selected_cliente_id");
            if ((role === 3 || role === 4) && selectedCliente) {
                payload.cliente_id = selectedCliente;
            }

            this.guardandoFechaLiquidaciones = true;
            axios
                .post(
                    "api/facturas-recibidas-cambiar-fecha-liquidaciones",
                    payload
                )
                .then((res) => {
                    const fecha = res.data?.fecha;
                    ids.forEach((id) => {
                        const row = this.facturaRecibidas.find((f) => f.id === id);
                        if (row && fecha) {
                            row.fecha_resumen_liquidacion = fecha;
                        }
                    });
                    this.cerrarDialogFechaLiquidaciones();
                    $toast.sucs(
                        res.data?.message ||
                            "Fecha del resumen actualizada correctamente"
                    );
                })
                .catch((err) => {
                    const data = err.response?.data;
                    $toast.error(
                        data?.message ||
                            data?.error ||
                            (data?.errors &&
                                Object.values(data.errors).flat().join(" ")) ||
                            "No se pudo cambiar la fecha del resumen"
                    );
                })
                .finally(() => {
                    this.guardandoFechaLiquidaciones = false;
                });
        },
        generarRemesa() {
            if (this.selected.length === 0) {
                return;
            }
            const ids = this.selected.map((row) => row.id);
            const payload = {
                ids,
                user_id: this.effectiveUserId,
            };
            const role = parseInt(localStorage.getItem("role"), 10);
            const selectedCliente = localStorage.getItem("selected_cliente_id");
            if ((role === 3 || role === 4) && selectedCliente) {
                payload.cliente_id = selectedCliente;
            }

            this.generandoRemesa = true;
            axios
                .post("api/facturas-recibidas-generar-remesa", payload, {
                    responseType: "blob",
                })
                .then((response) => {
                    const blob = response.data;
                    const type = blob.type || "";
                    if (
                        type.includes("json") ||
                        type.includes("text")
                    ) {
                        blob.text().then((t) => {
                            try {
                                const j = JSON.parse(t);
                                const msg =
                                    j.message ||
                                    j.error ||
                                    (j.errors &&
                                        Object.values(j.errors)
                                            .flat()
                                            .join(" ")) ||
                                    "Error al generar la remesa";
                                $toast.error(msg);
                            } catch {
                                $toast.error("Error al generar la remesa");
                            }
                        });
                        return;
                    }

                    const disposition =
                        response.headers["content-disposition"] || "";
                    let filename = "REMESA.xml";
                    const match = disposition.match(
                        /filename="?([^";\n]+)"?/i
                    );
                    if (match && match[1]) {
                        filename = match[1].trim();
                    }

                    const url = URL.createObjectURL(blob);
                    const link = document.createElement("a");
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);

                    const avisosHeader =
                        response.headers["x-remesa-avisos"] ||
                        response.headers["X-Remesa-Avisos"];
                    if (avisosHeader) {
                        try {
                            const avisos = JSON.parse(
                                decodeURIComponent(
                                    escape(atob(avisosHeader))
                                )
                            );
                            const incluidos =
                                avisos?.puntos_venta_incluidos?.length > 0
                                    ? avisos.puntos_venta_incluidos.join(", ")
                                    : null;
                            const textos =
                                avisos?.mensajes?.length > 0
                                    ? avisos.mensajes.join(" ")
                                    : null;
                            if (textos) {
                                let msg = "Remesa descargada con avisos: " + textos;
                                if (incluidos) {
                                    msg +=
                                        " Puntos de venta en el fichero: " +
                                        incluidos +
                                        ".";
                                }
                                $toast.warn(msg, { duration: 12000 });
                            } else if (incluidos) {
                                $toast.sucs(
                                    "Remesa descargada. Puntos de venta: " +
                                        incluidos
                                );
                            } else {
                                $toast.sucs("Remesa generada y descargada");
                            }
                        } catch {
                            $toast.sucs(
                                "Remesa generada. Revise Datos de Empresa y puntos de venta."
                            );
                        }
                    } else {
                        $toast.sucs("Remesa generada y descargada");
                    }
                })
                .catch((err) => {
                    const data = err.response?.data;
                    if (data instanceof Blob) {
                        data.text().then((t) => {
                            try {
                                const j = JSON.parse(t);
                                $toast.error(
                                    j.message ||
                                        (j.errors &&
                                            Object.values(j.errors)
                                                .flat()
                                                .join(" ")) ||
                                        "Error al generar la remesa"
                                );
                            } catch {
                                $toast.error("Error al generar la remesa");
                            }
                        });
                    } else {
                        $toast.error(
                            data?.message ||
                                data?.error ||
                                "Error al generar la remesa"
                        );
                    }
                })
                .finally(() => {
                    this.generandoRemesa = false;
                });
        },
        verPdfAutofactura(item) {
            if (!item?.id) {
                return;
            }
            const result = abrirPdfEnNuevaPestana(
                `api/facturas-recibidas-pdf/${item.id}`
            );
            if (!result.ok) {
                $toast.error(
                    "Permita ventanas emergentes para ver el PDF en otra pestaña"
                );
            }
        },
        verPdfResumenLiquidacion(item) {
            if (!item?.id) {
                return;
            }
            const result = abrirPdfEnNuevaPestana(
                `api/facturas-recibidas-resumen-liquidacion-pdf/${item.id}`
            );
            if (!result.ok) {
                $toast.error(
                    "Permita ventanas emergentes para ver el PDF en otra pestaña"
                );
            }
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
            this.selected = [];
            this.restaurarFiltroFechasDesdeStorage();
            this.getFactRecibidas();
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        effectiveUserId() {
            return effectiveBusinessUserId();
        },
        facturasRecibidasFiltradas() {
            return this.facturaRecibidas.filter((row) =>
                itemPasaFiltroFecha(row.fecha, this.fechaDesde, this.fechaHasta)
            );
        },
        puedeCambiarFechaLiquidaciones() {
            return (
                this.selected.length > 0 &&
                this.selected.some((row) => row.resumen_liquidacion)
            );
        },
    },
};
</script>

<style scoped>
.btn-guardar-fecha-liquidaciones {
    background-color: #dcff2e !important;
    color: #000 !important;
    font-weight: 600;
}

.btn-guardar-fecha-liquidaciones:hover,
.btn-guardar-fecha-liquidaciones:focus,
.btn-guardar-fecha-liquidaciones:active {
    background-color: #dcff2e !important;
    color: #000 !important;
}

.btn-guardar-fecha-liquidaciones:disabled {
    opacity: 0.55;
}
</style>
