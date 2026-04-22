<template>
    <VCard title="Nueva autofactura">
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
                            label="Nº factura (CO-n)"
                            hint="Formato CO-n (n crece según necesidad). Si lo deja vacío, se asignará el siguiente correlativo."
                            persistent-hint></VTextField>
                    </VCol>
                </VRow>

                <VRow
                    dense
                    class="mt-2">
                    <VCol
                        cols="12"
                        md="4">
                        <VCheckbox
                            v-model="facturaRec.contabilizado"
                            label="Contabilizado"
                            hide-details
                            density="compact" />
                    </VCol>
                </VRow>

                <VRow
                    dense
                    class="mt-5">
                    <VCol
                        cols="12"
                        md="12">
                        <p><strong>DATOS DEL SERVICIO</strong></p>
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
                        md="4">
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
                        md="1">
                        <!-- <VTextField
              hide-details
              filled
              type="number"
              step="0.01"
              v-model="servicio.iva"
              label="IVA"
              required
              suffix="%"
            ></VTextField> -->

                        <VSelect
                            v-model="servicio.iva"
                            :items="array_iva"
                            item-value="descripcion"
                            item-title="descripcion"
                            filled
                            label="IVA"
                            value="21"
                            suffix="%"></VSelect>
                    </VCol>
                </VRow>
                <VRow dense>
                    <VCol
                        class="mt-1"
                        cols="6"
                        md="5"
                        sm="12">
                        <VBtn
                            rounded="pill"
                            @click="addService"
                            :disabled="isloading"
                            >agregar servicio</VBtn
                        >
                        <!-- Espacio para errores -->
                    </VCol>
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
                <VRow dense>
                    <VCol
                        cols="12"
                        md="12">
                        <VDataTable
                            :headers="headers"
                            :items="facturaRec.servicios"
                            disable-pagination
                            hide-default-footer
                            item-key="id"
                            class="elevation-1">
                            <template v-slot:item.cantidad="{ item }">{{
                                formatCantidadFactura(item.cantidad)
                            }}</template>
                            <template v-slot:item.precio="{item}"
                                >{{ format_precio_autofactura(item.precio) }}</template
                            >
                            <template v-slot:item.dcto="{item}"
                                >{{ item.dcto }}%</template
                            >
                            <template v-slot:item.iva="{item}"
                                >{{ item.iva }}%</template
                            >
                            <template v-slot:item.total="{item}"
                                >{{ importeBaseLinea(item) }}</template
                            >
                            <template v-slot:item.action="{item}">
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
                        </VDataTable>
                    </VCol>
                    <!-- <VRow><pre style="font-size:10px !important;">{{recibo.servicios}}</pre></VRow> -->
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

                <VRow>
                    <VCol
                        cols="12"
                        md="4">
                        <VSelect
                            v-model="facturaRec.retencion_id"
                            :items="retenciones"
                            item-title="descripcion"
                            item-value="id"
                            filled
                            label="retencion"
                            suffix="%"></VSelect>
                    </VCol>
                    <VCol
                        cols="12"
                        md="8">
                        <div class="d-flex justify-end">
                            <div
                                class="flex-column"
                                style="width: 90%">
                                <p class="d-flex">
                                    <strong>Importe bruto:</strong
                                    ><VSpacer></VSpacer>
                                    {{ format_precio_autofactura(importeBruto) }}
                                </p>
                                <p class="d-flex">
                                    <strong>Base imponible:</strong
                                    ><VSpacer></VSpacer>
                                    {{ format_precio_autofactura(subtotal) }}
                                </p>
                                <p class="d-flex">
                                    <strong>IVA 21%:</strong
                                    ><VSpacer></VSpacer>
                                    21 %
                                </p>
                                <p class="d-flex">
                                    <strong>Cuota:</strong
                                    ><VSpacer></VSpacer>
                                    {{ format_precio_autofactura(cuotaIva) }}
                                </p>
                                <p class="d-flex">
                                    <strong>Retención:</strong
                                    ><VSpacer></VSpacer> -{{
                                        format_precio_autofactura(retencion)
                                    }}
                                </p>
                                <p class="d-flex">
                                    <strong>Total FRA:</strong
                                    ><VSpacer></VSpacer>
                                    {{ format_precio_autofactura(total) }}
                                </p>
                            </div>
                        </div>
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
                        path: `/lista-facturas-recibidas`,
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
import DialogArticulos from "./../articulos/DialogArticulos.vue";
import { format_precio_autofactura } from "@/utils/format_precio.js";

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
            retenciones: [],
            facturaRec: {
                id: null,
                fecha: new Date().toISOString().substr(0, 10),
                descripcion: "",
                proveedor_id: null,
                user_id: null,
                servicios: [],
                retencion_id: null,
                nro_factura: null,
                contabilizado: false,
            },
            servicio: {
                id: null,
                concepto: "",
                cantidad: "",
                precio: "",
                dcto: 0,
                iva: 21,
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
                    title: "Cantidad",
                    value: "cantidad",
                },
                {
                    title: "Precio",
                    value: "precio",
                },
                {
                    title: "Descuento",
                    value: "dcto",
                },
                {
                    title: "IVA",
                    value: "iva",
                },
                {
                    title: "Importe",
                    value: "total",
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ],
            subtotal: 0,
            retencion: 0,
            total: 0,
            totales_ivas: [],
            edit_item: {},
            array_iva: [],
        };
    },

    created() {
        this.facturaRec.user_id = this.effectiveUserId;
        this.getSiguienteNroCo();
        this.getProveedores();
        this.getRetenciones();
        this.getArrayIva();
        this.getServicios();
    },

    methods: {
        format_precio_autofactura,
        importeBaseLinea(item) {
            const c = parseFloat(item.cantidad);
            const p = parseFloat(item.precio);
            const d = parseFloat(item.dcto) || 0;
            if (Number.isNaN(c) || Number.isNaN(p)) {
                return "";
            }
            const bruto = c * p;
            const base = bruto * (1 - d / 100);
            return this.format_precio_autofactura(base);
        },
        formatCantidadFactura(val) {
            const n = Number(val);
            if (Number.isNaN(n)) {
                return "";
            }
            return Number.isInteger(n)
                ? String(n)
                : n.toLocaleString("es-ES", {
                      minimumFractionDigits: 0,
                      maximumFractionDigits: 4,
                  });
        },
        getSiguienteNroCo() {
            axios
                .get(`api/facturas-recibidas-siguiente-co`)
                .then((res) => {
                    if (res.data?.nro) {
                        this.facturaRec.nro_factura = res.data.nro;
                    }
                })
                .catch(() => {
                    this.facturaRec.nro_factura = "CO-1";
                });
        },
        getServicios() {
            axios
                .get(
                    `api/get-servicios?venta=0`
                )
                .then(
                    (res) => {
                        this.servicios = res.data;
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
        getRetenciones() {
            axios.get("api/get-retencion").then(
                (res) => {
                    this.retenciones = res.data.success;
                },
                (res) => {
                    $toast.error("Error consultando retenciones");
                }
            );
        },

        saveFactRecibidas() {
            this.$refs.form.validate();

            if (!this.formValid) return;

            let formData = new FormData();
            formData.append("id", this.facturaRec.id);
            formData.append("user_id", this.facturaRec.user_id);
            formData.append("fecha", this.facturaRec.fecha);
            formData.append("descripcion", this.facturaRec.descripcion);
            formData.append("proveedor_id", this.facturaRec.proveedor_id);
            formData.append("retencion_id", this.facturaRec.retencion_id);
            formData.append("total", this.total);
            const nroTrim = (this.facturaRec.nro_factura ?? "").toString().trim();
            formData.append(
                "nro_factura",
                nroTrim === "" ? "" : this.facturaRec.nro_factura
            );
            formData.append(
                "servicios",
                JSON.stringify(this.facturaRec.servicios)
            );
            formData.append(
                "contabilizado",
                this.facturaRec.contabilizado ? "1" : "0"
            );

            axios
                .post(`api/facturas-recibidas`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(
                    (res) => {
                        $toast.sucs("Autofactura guardada con éxito");
                        this.$router.push("/lista-facturas-recibidas");
                    },
                    (res) => {
                        $toast.error("Error guardando autofactura");
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
            let iva = (subtotal * this.servicio.iva) / 100;
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
                iva: 21,
                total: 0,
                id_servicio: this.servicio.id_servicio,
            };
        },

        calcularTotales(lista_servicios) {
            const importe_bruto = lista_servicios.reduce((acc, servicio) => {
                const c = parseFloat(servicio.cantidad);
                const p = parseFloat(servicio.precio);
                const bruto = (Number.isNaN(c) ? 0 : c) * (Number.isNaN(p) ? 0 : p);
                return acc + bruto;
            }, 0);

            let sub_total = lista_servicios.reduce((acc, servicio) => {
                let subtotal = servicio.cantidad * servicio.precio;
                let dcto = (subtotal * servicio.dcto) / 100;
                subtotal = subtotal - dcto;
                let total = isNaN(parseFloat(subtotal))
                    ? 0
                    : parseFloat(subtotal);
                return acc + total;
            }, 0);

            // Objeto para almacenar los totales de IVA
            let totalesIva = {};
            lista_servicios.forEach((element) => {
                let iva = element.iva;
                let subtotal = element.cantidad * element.precio;
                let dcto = (subtotal * element.dcto) / 100;
                subtotal = subtotal - dcto;
                let iva_valor = (subtotal * iva) / 100;

                // Verificar si ya existe un total de IVA para el tipo de IVA actual
                if (totalesIva.hasOwnProperty(iva)) {
                    // Si existe, sumar al total existente
                    totalesIva[iva] += iva_valor;
                } else {
                    // Si no existe, crear un nuevo total de IVA para el tipo de IVA actual
                    totalesIva[iva] = iva_valor;
                }
            });
            // Convertir los totales de IVA de nuevo a un array de objetos
            this.totales_ivas = Object.keys(totalesIva).map((iva) => {
                return {
                    titulo: `IVA ${iva}%`,
                    value: totalesIva[iva],
                    iva: parseInt(iva),
                };
            });

            const cuota = this.totales_ivas.reduce((acc, row) => acc + row.value, 0);

            const retRow = this.retenciones?.find(
                (element) => element.id == this.facturaRec.retencion_id
            );
            const retencion_procentaje = retRow?.descripcion;
            let retencion_valor = 0;
            if (typeof retencion_procentaje === "number") {
                retencion_valor = (sub_total * retencion_procentaje) / 100;
            }

            let total = sub_total - retencion_valor;
            const sumaIvas = this.totales_ivas.reduce(
                (acc, iva) => acc + iva.value,
                0
            );
            const totalFra = total + sumaIvas;
            this.importeBruto = Math.round(importe_bruto * 100) / 100;
            this.subtotal = Math.round(sub_total * 100) / 100;
            this.cuotaIva = Math.round(cuota * 100) / 100;
            this.retencion = Math.round(retencion_valor * 100) / 100;
            this.total = Math.round(totalFra * 100) / 100;
        },
        getArrayIva() {
            axios.get(`api/get-iva`).then(
                (res) => {
                    this.array_iva = res.data.success;
                },
                (err) => {
                    $toast.error("Error consultando opciones de iva");
                }
            );
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
    },

    watch: {
        "servicio.id_servicio"(val) {
            const servicio = this.servicios.find((ele) => ele.id == val);
            this.servicio.concepto = servicio?.descripcion;
            this.servicio.precio = servicio?.precio;
            // Si el servicio tiene IVA asociado, seleccionarlo automáticamente
            if (servicio?.iva && servicio.iva.descripcion) {
                this.servicio.iva = servicio.iva.descripcion;
            }
        },
        "facturaRec.retencion_id"(val) {
            if (this.facturaRec.servicios.length > 0) {
                this.calcularTotales(this.facturaRec.servicios);
            }
        },
    },
};
</script>
