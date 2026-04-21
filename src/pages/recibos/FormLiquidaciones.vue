<template>
    <VCard title="Nueva liquidación">
        <VDivider></VDivider>

        <loader v-if="isloading"></loader>

        <VCardText>
            <VForm
                ref="form"
                v-model="formValid"
                class="mt-5">
                <VRow dense>
                    <VCol
                        cols="12"
                        md="4">
                        <AppDateTimePicker
                            v-model="facturaRec.fecha"
                            label="Fecha"
                            prepend-icon="ri-calendar-fill"
                            :error-messages="
                                errors.errors.fecha
                                    ? errors.errors.fecha[0]
                                    : null
                            " />
                    </VCol>

                    <VCol
                        cols="12"
                        md="4">
                        <VSelect
                            filled
                            v-model="facturaRec.proveedor_id"
                            :error-messages="
                                errors.errors.proveedor_id
                                    ? errors.errors.proveedor_id[0]
                                    : null
                            "
                            :items="proveedores"
                            item-title="nombre"
                            item-value="id"
                            label="Punto de venta"
                            :rules="[requiredValidator]"></VSelect>
                    </VCol>

                    <VCol
                        cols="12"
                        md="4">
                        <VTextField
                            filled
                            v-model="facturaRec.nro_factura"
                            label="Nro. de liquidación"></VTextField>
                    </VCol>
                </VRow>

                <VRow
                    dense
                    class="mt-5">
                    <VCol
                        cols="12"
                        md="12">
                        <p><strong>DATOS DEL ARTÍCULO</strong></p>
                    </VCol>
                    <VCol
                        cols="12"
                        md="2">
                        <VAutocomplete
                            hide-details
                            filled
                            :items="servicios"
                            v-model="servicio.id_servicio"
                            item-title="descripcion"
                            item-value="id"
                            label="Artículo"
                            required></VAutocomplete>
                    </VCol>
                    <VCol
                        cols="12"
                        md="3">
                        <VTextField
                            hide-details
                            filled
                            v-model="servicio.concepto"
                            label="Concepto"
                            required></VTextField>
                    </VCol>
                    <VCol
                        cols="12"
                        md="2">
                        <VTextField
                            hide-details
                            filled
                            type="number"
                            step="0.01"
                            v-model="servicio.precio"
                            label="Precio"
                            required></VTextField>
                    </VCol>
                    <VCol
                        cols="12"
                        md="2">
                        <VTextField
                            hide-details
                            filled
                            type="number"
                            step="0.01"
                            v-model="servicio.cantidad"
                            label="Cantidad"
                            required></VTextField>
                    </VCol>
                    <VCol
                        cols="12"
                        md="1">
                        <VTextField
                            hide-details
                            filled
                            type="number"
                            step="0.01"
                            v-model="servicio.dcto"
                            label="Descuento"
                            required
                            suffix="%"></VTextField>
                    </VCol>
                    <VCol
                        cols="12"
                        md="2"
                        class="d-flex align-end pb-1">
                        <VBtn
                            block
                            rounded="pill"
                            @click="addService"
                            :disabled="isloading">
                            Agregar artículo
                        </VBtn>
                    </VCol>
                </VRow>
                <VRow dense>
                    <VCol
                        v-if="errors.errors.servicios"
                        cols="12"
                        sm="12"
                        md="3"
                        lg="3"
                        xl="3">
                        <VAlert
                            outlined
                            color="red"
                            ><strong>{{
                                errors.errors.servicios[0]
                            }}</strong></VAlert
                        >
                    </VCol>
                </VRow>
                <VRow
                    dense
                    align="stretch"
                    class="mt-2 liquidacion-fila-tablas">
                    <VCol
                        cols="12"
                        lg="7">
                        <p class="text-caption text-medium-emphasis mb-1">
                            Líneas de liquidación
                        </p>
                        <VDataTable
                            :headers="headers"
                            :items="lineasLiquidacionTablaItems"
                            :row-props="liquidacionRowPropsLineas"
                            disable-pagination
                            hide-default-footer
                            item-key="__key"
                            density="compact"
                            class="elevation-1 rounded">
                            <template v-slot:item.concepto="{ item }">
                                <span
                                    :class="
                                        item._total ? 'font-weight-bold' : ''
                                    "
                                    >{{ item.concepto }}</span
                                >
                            </template>
                            <template v-slot:item.precio="{ item }">
                                <span v-if="!item._total"
                                    >{{ formatPrice(item.precio) }}€</span
                                >
                            </template>
                            <template v-slot:item.dcto="{ item }">
                                <span v-if="!item._total"
                                    >{{ item.dcto }}%</span
                                >
                            </template>
                            <template v-slot:item.total="{ item }">
                                <span
                                    :class="
                                        item._total ? 'font-weight-bold' : ''
                                    "
                                    >{{ formatPrice(item.total) }}€</span
                                >
                            </template>
                            <template v-slot:item.action="{ item }">
                                <template v-if="!item._total">
                                    <VIcon
                                        @click="setItem(item)"
                                        small
                                        class="mr-2"
                                        color="blue"
                                        >ri-pencil-line</VIcon
                                    >
                                    <VIcon
                                        class="ml-7"
                                        @click="deleteItem(item)"
                                        small
                                        color="red"
                                        >ri-delete-bin-line</VIcon
                                    >
                                </template>
                            </template>
                        </VDataTable>
                    </VCol>
                    <VCol
                        cols="12"
                        lg="5">
                        <p class="text-caption text-medium-emphasis mb-1">
                            Comisiones del punto de venta
                        </p>
                        <VCard
                            v-if="deduccionesComisionLines.length"
                            variant="outlined"
                            class="liquidacion-comisiones-panel h-100 elevation-1"
                            rounded="lg">
                            <VCardText class="pa-0 pb-3 liquidacion-comisiones-card-inner">
                                <VDataTable
                                    :headers="headersComision"
                                    :items="comisionesTablaItems"
                                    :row-props="comisionRowPropsComision"
                                    disable-pagination
                                    hide-default-footer
                                    item-key="__key"
                                    density="compact"
                                    class="bg-transparent">
                                    <template v-slot:item.concepto="{ item }">
                                        <span
                                            :class="
                                                item._total
                                                    ? 'font-weight-bold'
                                                    : ''
                                            "
                                            >{{ item.concepto }}</span
                                        >
                                    </template>
                                    <template v-slot:item.detalleUnitario="{
                                        item,
                                    }">
                                        <span
                                            v-if="!item._total"
                                            class="text-medium-emphasis"
                                            >{{ item.detalleUnitario }}</span
                                        >
                                    </template>
                                    <template v-slot:item.monto="{ item }">
                                        <span
                                            class="text-error font-weight-medium"
                                            >-{{
                                                formatPrice(item.monto)
                                            }}€</span
                                        >
                                    </template>
                                </VDataTable>
                            </VCardText>
                        </VCard>
                        <VCard
                            v-else
                            variant="outlined"
                            class="d-flex align-center justify-center pa-6 text-center h-100 liquidacion-comisiones-vacio"
                            rounded="lg">
                            <div class="text-medium-emphasis text-body-2">
                                <VIcon
                                    class="mb-2"
                                    size="large"
                                    >ri-information-line</VIcon
                                >
                                <div>
                                    No hay comisiones configuradas o no aplican
                                    a las líneas actuales.
                                </div>
                            </div>
                        </VCard>
                    </VCol>
                </VRow>

                <VRow dense>
                    <VCol cols="12">
                        <VCard
                            class="liquidacion-resumen-card overflow-hidden"
                            elevation="2"
                            variant="outlined"
                            rounded="lg">
                            <div
                                class="liquidacion-resumen-head px-5 py-4 d-flex align-center ga-3">
                                <VAvatar
                                    color="surface-variant"
                                    variant="flat"
                                    size="44"
                                    rounded="lg">
                                    <VIcon
                                        class="liquidacion-resumen-head-icon"
                                        size="large"
                                        >ri-calculator-line</VIcon
                                    >
                                </VAvatar>
                                <div>
                                    <div class="text-h6 liquidacion-resumen-titulo">
                                        Resumen de importes
                                    </div>
                                    <div
                                        class="text-caption liquidacion-resumen-subtitulo">
                                        Subtotal, IVA artículos, comisiones (base + IVA 21%) e importe
                                    </div>
                                </div>
                            </div>
                            <VCardText class="pa-5 pa-md-6">
                                <div class="liquidacion-resumen-grid">
                                    <div
                                        class="liquidacion-resumen-line text-body-1">
                                        <span class="text-medium-emphasis"
                                            >Subtotal (base imponible)</span
                                        >
                                        <span class="font-weight-medium">{{
                                            format_precio(subtotal)
                                        }}</span>
                                    </div>
                                    <div
                                        class="liquidacion-resumen-line text-body-1">
                                        <span class="text-medium-emphasis"
                                            >IVA (según artículos)</span
                                        >
                                        <span class="font-weight-medium">{{
                                            format_precio(totalIva)
                                        }}</span>
                                    </div>
                                    <div
                                        v-if="deduccionesComisionLines.length"
                                        class="liquidacion-resumen-line text-body-1">
                                        <span class="text-medium-emphasis"
                                            >Comisiones (base imponible)</span
                                        >
                                        <span
                                            class="font-weight-bold text-error"
                                            >-{{
                                                format_precio(
                                                    totalDeduccionComisiones
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        v-if="deduccionesComisionLines.length"
                                        class="liquidacion-resumen-line text-body-1">
                                        <span class="text-medium-emphasis"
                                            >IVA comisiones ({{
                                                ivaPctComisionesLiquidacion
                                            }}%)</span
                                        >
                                        <span
                                            class="font-weight-bold text-error"
                                            >-{{
                                                format_precio(
                                                    ivaComisionesLiquidacion
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <VDivider class="my-3" thickness="2" />
                                    <div
                                        class="liquidacion-resumen-total d-flex justify-space-between align-center flex-wrap ga-2">
                                        <span
                                            class="text-h6 font-weight-bold"
                                            >Importe a liquidar</span
                                        >
                                        <span
                                            class="text-h5 font-weight-bold text-high-emphasis"
                                            >{{ format_precio(total) }}</span
                                        >
                                    </div>
                                </div>
                            </VCardText>
                        </VCard>
                    </VCol>
                </VRow>

                <VRow>
                    <VCol cols="12">
                        <p><strong>Observaciones</strong></p>
                        <VTextarea
                            solo
                            v-model="facturaRec.descripcion"
                            placeholder="Escribe una descripción u observación sobre esta factura"></VTextarea>
                    </VCol>
                </VRow>

                <VRow v-if="facturaRec.id != null">
                    <!-- <a v-for="n,m in JSON.parse(item.pdf)" :key="m" target="_blank" :href="'/storage/albaranes/recibidos/' + n">
          <VIcon medium color="primary" class="mr-2">
            ri-file-line
          </VIcon>

        </a> -->
                </VRow>
            </VForm>
        </VCardText>

        <VDivider />

        <VCardText>
            <VRow dense>
                <VBtn
                    :to="{
                        path: `/lista-liquidaciones`,
                    }"
                    rounded="pill"
                    variant="outlined"
                    color="secondary"
                    class="mr-1">
                    volver
                </VBtn>
                <VBtn
                    rounded="pill"
                    @click="saveFactRecibidas"
                    :disabled="isloading"
                    >Guardar</VBtn
                >
            </VRow>
        </VCardText>

        <DialogArticulos
            @saved="SaveTipoServicio"
            v-model="dialog"
            :servicio="servicio_dialog"
            :venta="0"></DialogArticulos>
    </VCard>
</template>

<script>
import { formatPrice } from "@/@core/utils/formatters";
import DialogArticulos from "./../articulos/DialogArticulos.vue";

/** IVA aplicado al total de comisiones (misma lógica que factura por comisiones en backend). */
const IVA_PCT_COMISIONES_LIQUIDACION = 21;

export default {
    components: {
        DialogArticulos,
    },
    data() {
        return {
            formValid: false,
            dialog: false,
            servicio_dialog: {},
            servicios: [],
            proveedores: [],
            facturaRec: {
                id: null,
                fecha: new Date().toISOString().substr(0, 10),
                descripcion: "",
                proveedor_id: null,
                user_id: null,
                servicios: [],
                retencion_id: null,
                nro_factura: null,
            },
            servicio: {
                id: null,
                concepto: "",
                cantidad: "",
                precio: "",
                dcto: 0,
                iva: null,
                total: 0,
                id_servicio: null,
            },

            headers: [
                {
                    title: "Concepto",
                    align: "left",
                    value: "concepto",
                },
                {
                    title: "Precio",
                    value: "precio",
                },
                {
                    title: "Cantidad",
                    value: "cantidad",
                },
                {
                    title: "Descuento",
                    value: "dcto",
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
            headersComision: [
                {
                    title: "Concepto",
                    align: "left",
                    value: "concepto",
                },
                {
                    title: "Comisión",
                    align: "left",
                    value: "detalleUnitario",
                },
                {
                    title: "Importe",
                    align: "end",
                    value: "monto",
                    sortable: false,
                },
            ],
            subtotal: 0,
            totalIva: 0,
            total: 0,
            edit_item: {},
            comisionesProveedor: [],
            ivaPctComisionesLiquidacion: IVA_PCT_COMISIONES_LIQUIDACION,
        };
    },

    created() {
        this.facturaRec.user_id = this.effectiveUserId;
        this.getProveedores();
        this.getServicios();
        this.getSiguienteNroLiquidacion();
    },

    methods: {
        formatPrice,
        /** IVA del artículo: `iva_percent` del servicio o tipo `Iva.descripcion`; sin valor → 0 */
        resolverIvaArticulo(art) {
            if (!art || typeof art !== "object") {
                return 0;
            }
            const pctField = art.iva_percent;
            if (
                pctField !== null &&
                pctField !== undefined &&
                pctField !== ""
            ) {
                const n = Number(pctField);
                if (!Number.isNaN(n)) {
                    return n;
                }
            }
            const desc = art.iva && art.iva.descripcion;
            if (desc !== null && desc !== undefined && desc !== "") {
                const n = parseFloat(String(desc).replace(",", "."));
                if (!Number.isNaN(n)) {
                    return n;
                }
            }
            return 0;
        },
        loadComisionesProveedor() {
            const pid = this.facturaRec.proveedor_id;
            if (!pid) {
                this.comisionesProveedor = [];
                this.recalcTotalesSiHayLineas();
                return;
            }
            axios
                .get(`api/proveedor-comisiones/${pid}`)
                .then((res) => {
                    this.comisionesProveedor = res.data || [];
                    this.recalcTotalesSiHayLineas();
                })
                .catch(() => {
                    this.comisionesProveedor = [];
                    this.recalcTotalesSiHayLineas();
                });
        },
        recalcTotalesSiHayLineas() {
            if (
                this.facturaRec.servicios &&
                this.facturaRec.servicios.length > 0
            ) {
                this.calcularTotales(this.facturaRec.servicios);
            }
        },
        formatoPorcentajeComision(val) {
            const n = Number(val);
            if (Number.isNaN(n)) {
                return "0";
            }
            return formatPrice(n) || "0";
        },
        comisionRowPropsComision(data) {
            const item = data?.item;
            if (item?._total) {
                return {
                    class: "border-t",
                };
            }
            if (item?._ivaRow) {
                return {
                    class: "border-t border-opacity-50",
                };
            }
            return {};
        },
        liquidacionRowPropsLineas(data) {
            const item = data?.item;
            if (item?._total) {
                return {
                    class: "border-t",
                };
            }
            return {};
        },
        getSiguienteNroLiquidacion() {
            axios
                .get(`api/liquidaciones-siguiente-numero`)
                .then(
                    (res) => {
                        this.facturaRec.nro_factura = res.data.nro;
                    },
                    () => {
                        this.facturaRec.nro_factura = "CO-1";
                    }
                );
        },
        getServicios() {
            axios
                .get(
                    `api/get-servicios?venta=0`
                )
                .then(
                    (res) => {
                        this.servicios = res.data;
                        this.recalcTotalesSiHayLineas();
                    },
                    (err) => {
                        $toast.error("Error consultando artículos");
                    }
                );
        },
        getProveedores() {
            axios.get(`api/get-proveedores/` + this.facturaRec.user_id).then(
                (res) => {
                    this.proveedores = res.data;
                },
                (res) => {
                    $toast.error("Error consultando puntos de venta");
                }
            );
        },
        saveFactRecibidas() {
            this.$refs.form.validate();

            if (!this.formValid) return;

            if (
                !this.facturaRec.servicios ||
                this.facturaRec.servicios.length === 0
            ) {
                return $toast.error(
                    "Añada al menos una línea de artículo con cantidad y precio"
                );
            }
            const lineaInvalida = this.facturaRec.servicios.some((l) => {
                const c = parseFloat(l.cantidad);
                const p = parseFloat(l.precio);
                return (
                    !l.concepto ||
                    Number.isNaN(c) ||
                    c <= 0 ||
                    Number.isNaN(p) ||
                    p < 0
                );
            });
            if (lineaInvalida) {
                return $toast.error(
                    "Todas las líneas deben tener concepto, cantidad mayor que cero y precio válido"
                );
            }

            let formData = new FormData();
            formData.append("id", this.facturaRec.id);
            formData.append("user_id", this.facturaRec.user_id);
            formData.append("fecha", this.facturaRec.fecha);
            formData.append(
                "descripcion",
                this.facturaRec.descripcion == null
                    ? ""
                    : String(this.facturaRec.descripcion)
            );
            formData.append("proveedor_id", this.facturaRec.proveedor_id);
            formData.append("retencion_id", "");
            formData.append("total", this.total);
            formData.append("nro_factura", this.facturaRec.nro_factura);
            formData.append(
                "servicios",
                JSON.stringify(this.facturaRec.servicios)
            );

            axios
                .post(`api/liquidaciones`, formData)
                .then(
                    (res) => {
                        $toast.sucs("Liquidación guardada con éxito");
                        this.$router.push("/lista-liquidaciones");
                    },
                    (err) => {
                        const msg =
                            err.response?.data?.error ||
                            err.response?.data?.message ||
                            "Error guardando liquidación";
                        $toast.error(msg);
                    }
                );
        },
        SaveTipoServicio(data) {
            this.servicios.push(data);
            this.servicio.id_servicio = data.id;
            this.addService();
            this.dialog = false;
        },
        // funciones de la tabla de servicios
        addService() {
            /* Si el servicio a introducir esta vacio da error */
            if (this.servicio.concepto == "") {
                return $toast.error("Introduzca un concepto");
            }
            const cantidad = parseFloat(this.servicio.cantidad);
            const precio = parseFloat(this.servicio.precio);
            if (
                this.servicio.cantidad === "" ||
                this.servicio.cantidad === null ||
                Number.isNaN(cantidad) ||
                cantidad <= 0
            ) {
                return $toast.error("Indique una cantidad mayor que cero");
            }
            if (
                this.servicio.precio === "" ||
                this.servicio.precio === null ||
                Number.isNaN(precio) ||
                precio < 0
            ) {
                return $toast.error("Indique un precio válido (0 o más)");
            }
            if (this.servicio.id_servicio == null) {
                //this.dialog = true;
                this.servicio_dialog = {
                    descripcion: this.servicio.concepto,
                    precio: this.servicio.precio,
                    user_id: this.effectiveUserId,
                    venta: 0,
                };
                //return;
            }
            let subtotal = this.servicio.cantidad * this.servicio.precio;
            let dcto = (subtotal * this.servicio.dcto) / 100;
            subtotal = subtotal - dcto;
            const art = this.servicios.find(
                (e) => e.id == this.servicio.id_servicio
            );
            const parsedIva = parseFloat(this.servicio.iva);
            const ivaPct = Number.isFinite(parsedIva)
                ? parsedIva
                : this.resolverIvaArticulo(art);
            let iva = (subtotal * ivaPct) / 100;
            this.servicio.iva = ivaPct;
            this.servicio.total = parseFloat(subtotal + iva).toFixed(2);

            // this.facturaRec.servicios.push(this.servicio);
            this.updateOrPush(this.servicio);
            this.calcularTotales(this.facturaRec.servicios);
            this.resetServicio();
            $toast.sucs("Artículo añadido");
        },
        updateOrPush(servicio) {
            // let index = this.facturaRec.servicios.findIndex(
            //     (element) => element.id == servicio.id
            // );
            // return index > -1
            //     ? this.$set(this.facturaRec.servicios, index, servicio)
            //     : this.facturaRec.servicios.push(servicio);

            let index = this.facturaRec.servicios.findIndex((element) => {
                return JSON.stringify(element) == this.edit_item;
            });
            if (index > -1) {
                // this.$set(this.facturaRec.servicios, index, servicio);
                this.facturaRec.servicios[index] = servicio;
            } else {
                this.facturaRec.servicios.push(servicio);
            }
        },
        setItem(servicio) {
            this.servicio = JSON.parse(JSON.stringify(servicio));
            this.edit_item = JSON.stringify(servicio);
        },
        deleteItem(servicio) {
            //  Eliminamos el servicio de la lista de servicios a facturar
            let index = this.facturaRec.servicios.indexOf(servicio);
            this.facturaRec.servicios.splice(index, 1);
            // Restauramos los campos de datos de servicio para poder almacenar mas
            this.resetServicio();
            // Calculamos los totales de la factura
            this.calcularTotales(this.facturaRec.servicios);
        },
        resetServicio() {
            this.servicio = {
                id: null,
                concepto: "",
                cantidad: "",
                precio: "",
                dcto: 0,
                iva: null,
                total: 0,
                id_servicio: this.servicio.id_servicio,
            };
            const artReset = this.servicios.find(
                (e) => e.id == this.servicio.id_servicio
            );
            if (artReset) {
                this.servicio.iva = this.resolverIvaArticulo(artReset);
            }
        },

        calcularTotales(lista_servicios) {
            let sub_total = lista_servicios.reduce((acc, servicio) => {
                let subtotal = servicio.cantidad * servicio.precio;
                let dcto = (subtotal * servicio.dcto) / 100;
                subtotal = subtotal - dcto;
                let total = isNaN(parseFloat(subtotal))
                    ? 0
                    : parseFloat(subtotal);
                return acc + total;
            }, 0);

            let sumaIva = 0;
            lista_servicios.forEach((element) => {
                let subtotal = element.cantidad * element.precio;
                let dcto = (subtotal * element.dcto) / 100;
                subtotal = subtotal - dcto;
                const artLine = this.servicios.find(
                    (e) => e.id == element.id_servicio
                );
                const parsedLine = parseFloat(element.iva);
                const ivaPct = Number.isFinite(parsedLine)
                    ? parsedLine
                    : this.resolverIvaArticulo(artLine);
                sumaIva += (subtotal * ivaPct) / 100;
            });

            const comisionesConIva = this.totalComisionesConIvaLiquidacion;
            this.subtotal = sub_total;
            this.totalIva = sumaIva;
            this.total = sub_total + sumaIva - comisionesConIva;
        },
    },
    computed: {
        isloading() {
            return this.$store.getters.getloading;
        },

        errors() {
            return this.$store.getters.geterrors;
        },
        userID() {
            return localStorage.user_id;
        },
        effectiveUserId() {
            const role = parseInt(localStorage.getItem("role"));
            const selectedCliente = localStorage.getItem("selected_cliente_id");
            if (role === 3 && selectedCliente) {
                return selectedCliente;
            }
            return localStorage.getItem("user_id");
        },
        deduccionesComisionLines() {
            const lines = this.facturaRec.servicios || [];
            const coms = this.comisionesProveedor || [];
            if (!lines.length || !coms.length) {
                return [];
            }
            const map = new Map(
                coms.map((c) => [Number(c.servicio_id), c])
            );
            const out = [];
            for (const line of lines) {
                const sidRaw = line.id_servicio;
                const sid =
                    sidRaw != null && sidRaw !== ""
                        ? Number(sidRaw)
                        : NaN;
                if (!Number.isFinite(sid) || sid <= 0) {
                    continue;
                }
                const c = map.get(sid);
                if (!c) {
                    continue;
                }
                const cantidad = parseFloat(line.cantidad) || 0;
                const precio = parseFloat(line.precio) || 0;
                const dcto = parseFloat(line.dcto) || 0;
                const bruto = cantidad * precio;
                const baseTrasDcto = bruto * (1 - dcto / 100);
                let monto = 0;
                let detalleUnitario = "";
                if (c.tipo === "porcentaje") {
                    const pct = parseFloat(c.valor) || 0;
                    monto = baseTrasDcto * (pct / 100);
                    detalleUnitario = `${this.formatoPorcentajeComision(pct)} %`;
                } else {
                    const imp = parseFloat(c.valor) || 0;
                    monto = cantidad * imp;
                    detalleUnitario = `${formatPrice(imp)} € / ud.`;
                }
                out.push({
                    concepto: line.concepto || "—",
                    detalleUnitario,
                    monto,
                });
            }
            return out;
        },
        totalDeduccionComisiones() {
            return this.deduccionesComisionLines.reduce(
                (acc, row) => acc + row.monto,
                0
            );
        },
        ivaComisionesLiquidacion() {
            const base = this.totalDeduccionComisiones;
            return (
                Math.round(
                    (base * IVA_PCT_COMISIONES_LIQUIDACION) / 100 * 100
                ) / 100
            );
        },
        totalComisionesConIvaLiquidacion() {
            const base = this.totalDeduccionComisiones;
            const iva = this.ivaComisionesLiquidacion;
            return Math.round((base + iva) * 100) / 100;
        },
        comisionesTablaItems() {
            const lines = this.deduccionesComisionLines;
            if (!lines.length) {
                return [];
            }
            const rows = lines.map((row, idx) => ({
                ...row,
                __key: `com-line-${idx}`,
            }));
            rows.push({
                __key: "com-iva",
                _ivaRow: true,
                concepto: `IVA comisiones (${IVA_PCT_COMISIONES_LIQUIDACION}%)`,
                detalleUnitario: "",
                monto: this.ivaComisionesLiquidacion,
            });
            rows.push({
                __key: "com-total",
                _total: true,
                concepto: "Total comisiones (IVA incl.)",
                detalleUnitario: "",
                monto: this.totalComisionesConIvaLiquidacion,
            });
            return rows;
        },
        lineasLiquidacionTablaItems() {
            const lines = this.facturaRec.servicios || [];
            if (!lines.length) {
                return [];
            }
            const rows = lines.map((row, idx) => ({
                ...row,
                __key: `liq-line-${idx}`,
            }));
            if (lines.length > 1) {
                const sumaTotal = lines.reduce(
                    (acc, l) => acc + (parseFloat(l.total) || 0),
                    0
                );
                rows.push({
                    __key: "liq-total",
                    _total: true,
                    concepto: "Total liquidación",
                    precio: "",
                    cantidad: "",
                    dcto: "",
                    total: sumaTotal,
                });
            }
            return rows;
        },
    },

    watch: {
        "servicio.id_servicio"(val) {
            const art = this.servicios.find((ele) => ele.id == val);
            if (!art) {
                this.servicio.concepto = undefined;
                this.servicio.precio = undefined;
                this.servicio.iva = null;
                return;
            }
            this.servicio.concepto = art.descripcion;
            this.servicio.precio = art.precio;
            this.servicio.iva = this.resolverIvaArticulo(art);
        },
        "facturaRec.proveedor_id"() {
            this.loadComisionesProveedor();
        },
    },
};
</script>

<style scoped>
.liquidacion-resumen-head {
    background-color: rgb(var(--v-theme-surface-variant));
    border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}

.liquidacion-resumen-titulo {
    color: rgb(var(--v-theme-on-surface-variant));
    font-weight: 700;
}

.liquidacion-resumen-subtitulo {
    color: rgba(var(--v-theme-on-surface-variant), 0.78);
}

.liquidacion-resumen-head-icon {
    color: rgb(var(--v-theme-on-surface-variant)) !important;
    opacity: 0.85;
}

.liquidacion-resumen-grid {
    max-width: 28rem;
    margin-inline-start: auto;
}

.liquidacion-resumen-line {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 1rem;
    padding-block: 0.35rem;
}

.liquidacion-comisiones-vacio {
    min-block-size: 12rem;
}

/* Aire entre bloque líneas/comisiones y el resumen */
.liquidacion-fila-tablas {
    margin-bottom: 1.75rem;
}

@media (min-width: 1280px) {
    .liquidacion-fila-tablas {
        margin-bottom: 2.25rem;
    }
}

/* La última fila de la tabla de comisiones no queda pegada al borde inferior */
.liquidacion-comisiones-card-inner :deep(.v-data-table) {
    margin-bottom: 2px;
}
</style>
