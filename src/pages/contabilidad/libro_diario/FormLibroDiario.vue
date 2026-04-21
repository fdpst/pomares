<template>
    <div>
        <loader v-if="isloading"></loader>

        <VCard
            shaped
            class="pa-4">
            <VRow>
                <VCol cols="12">
                    <VToolbar
                        flat
                        color="#1d2735"
                        dark
                        style="border-radius: 5px"
                        class="ps-4">
                        <VIcon
                            class="white--text"
                            style="font-size: 45px"
                            >mdi mdi-account-supervisor-circle</VIcon
                        >
                        <VToolbarTitle
                            ><h3 class="text-white">
                                Nuevo Asiento
                            </h3></VToolbarTitle
                        >
                    </VToolbar>

                    <VForm
                        ref="form"
                        id="form"
                        class="mt-3">
                        <VRow
                            justify="end"
                            style="padding: 20px">
                            <VBtn
                                v-if="asiento.id"
                                @click="saveAsiento"
                                :disabled="isloading"
                                color="success"
                                class="white--text"
                                >Actualizar</VBtn
                            >
                            <VBtn
                                color="secondary"
                                class="white--text"
                                style="margin-left: 10px"
                                @click="cancelar">
                                Cancelar
                            </VBtn>
                        </VRow>

                        <VRow>
                            <VCol
                                cols="12"
                                md="4">
                                <VAutocomplete
                                    v-model="asiento.tipo_apunte_id"
                                    :items="tipo_asiento"
                                    item-title="descripcion"
                                    item-value="id"
                                    label="Tipo de asiento"
                                    outlined>
                                </VAutocomplete>
                            </VCol>

                            <VCol
                                cols="12"
                                md="4">
                                <VAutocomplete
                                    v-model="asiento.apunte_predefinido_id"
                                    :items="asiento_predefinido"
                                    item-title="descripcion"
                                    item-value="id"
                                    label="Asiento predefinido"
                                    outlined
                                    @change="selectContacto">
                                </VAutocomplete>
                            </VCol>

                            <VCol
                                cols="12"
                                md="4">
                                <AppDateTimePicker
                                    v-model="asiento.fecha"
                                    label="Fecha"
                                    prepend-icon="ri-calendar-fill" />
                            </VCol>

                            <template v-if="hide_contacto">
                                <template v-if="asiento.cliente_id != null">
                                    <VCol
                                        cols="12"
                                        md="6">
                                        <!-- <clienteSelectVue
                      :label="'Clientes'"
                      v-model="asiento.cliente_id"
                      :value="asiento.cliente_id"
                      :outlined="true"
                    >
                    </clienteSelectVue> -->

                                        <VAutocomplete
                                            label="Clientes"
                                            v-model="asiento.cliente_id"
                                            :items="clientes"
                                            item-title="nombre"
                                            item-value="id"
                                            @input="onCustomerSearch"
                                            outlined></VAutocomplete>
                                    </VCol>
                                    <VCol
                                        cols="12"
                                        md="6">
                                        <VAutocomplete
                                            v-model="asiento.factura_id"
                                            :items="facturas"
                                            item-title="id"
                                            item-value="id"
                                            label="Facturas"
                                            outlined
                                            @change="setCuentasVentas">
                                        </VAutocomplete>
                                    </VCol>
                                </template>
                                <template v-else>
                                    <VCol
                                        cols="12"
                                        md="6">
                                        <VAutocomplete
                                            v-model="asiento.proveedor_id"
                                            :items="proveedores"
                                            item-title="nombre"
                                            item-value="id"
                                            label="Proveedor"
                                            outlined
                                            @change="getFacturasEntrantes">
                                        </VAutocomplete>
                                    </VCol>
                                    <VCol
                                        cols="12"
                                        md="6">
                                        <VAutocomplete
                                            v-model="
                                                asiento.factura_recibida_id
                                            "
                                            :items="facturas_entrantes"
                                            item-title="nro_factura"
                                            item-value="id"
                                            label="Facturas"
                                            outlined
                                            @change="setCuentasCompras">
                                        </VAutocomplete>
                                    </VCol>
                                </template>
                            </template>
                        </VRow>

                        <VRow justify="center">
                            <VCol cols="12">
                                <multiple-select
                                    :headers="headers_cuentas"
                                    title="Cuentas"
                                    :elementos="asiento.cuentas"
                                    show="nombre"
                                    @create="createCuenta"
                                    @delete="deleteCuenta"
                                    @getEstado="updateCuentaS"
                                    @update="updateCuenta">
                                    <VRow>
                                        <VCol
                                            cols="12"
                                            md="6">
                                            <VAutocomplete
                                                outlined
                                                v-model="
                                                    cuenta.cuenta_contable_id
                                                "
                                                :items="cuentas"
                                                item-title="numero"
                                                item-value="id"
                                                label="Cuenta"
                                                :rules="required_val">
                                            </VAutocomplete>
                                        </VCol>

                                        <VCol
                                            cols="12"
                                            md="6">
                                            <VTextField
                                                outlined
                                                v-model="cuenta.tag"
                                                label="Tag">
                                            </VTextField>
                                        </VCol>
                                    </VRow>
                                    <VRow>
                                        <VCol
                                            cols="12"
                                            md="6">
                                            <VTextField
                                                outlined
                                                v-model="cuenta.debe"
                                                label="Debe"
                                                type="number"
                                                :rules="numberInput"
                                                :disabled="debeDisabled">
                                            </VTextField>
                                        </VCol>
                                        <VCol
                                            cols="12"
                                            md="6">
                                            <VTextField
                                                outlined
                                                v-model="cuenta.haber"
                                                label="Haber"
                                                type="number"
                                                :rules="numberInput"
                                                :disabled="haberDisabled">
                                            </VTextField>
                                        </VCol>
                                    </VRow>

                                    <VRow>
                                        <VCol cols="12">
                                            <VTextField
                                                outlined
                                                v-model="cuenta.descripcion"
                                                label="Descripción"
                                                :rules="required_val">
                                            </VTextField>
                                        </VCol>
                                    </VRow>
                                </multiple-select>
                            </VCol>

                            <VCol
                                cols="12"
                                v-if="campos_requeridos">
                                <h6 style="color: red; text-align: center">
                                    Edite las lineas generadas automaticamente y
                                    llene los campos requeridos
                                </h6>
                            </VCol>
                        </VRow>

                        <VRow>
                            <VCol cols="12">
                                <VTextarea
                                    v-model="asiento.nota"
                                    label="Nota"
                                    outlined>
                                </VTextarea>
                            </VCol>

                            <!-- <VCol cols="12" md="6">
                <VFileInput
                  id="file"
                  v-model="file"
                  label="Subir archivo"
                  name="file"
                  required
                  show-size
                  counter
                  accept="application/pdf"
                ></VFileInput>
              </VCol> -->
                        </VRow>
                    </VForm>

                    <VRow>
                        <VCol cols="10"><h3>Importes</h3></VCol>
                        <VCol cols="2"
                            ><VBtn
                                fab
                                dark
                                small
                                color="info"
                                @click="
                                    asiento.importes.push({
                                        iva: 21,
                                        subtotal: 0,
                                        importe_iva: 0,
                                    })
                                ">
                                <VIcon
                                    color="white"
                                    dark>
                                    ri-add-line
                                </VIcon>
                            </VBtn></VCol
                        >
                    </VRow>

                    <VRow
                        class="mt-3"
                        v-for="(importe, index) in asiento.importes">
                        <VCol
                            cols="12"
                            md="4"
                            v-bind:key="index">
                            <VTextField
                                label="Impuesto (%)"
                                v-model="importe.iva"
                                outlined
                                readonly></VTextField>
                        </VCol>
                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                label="Subtotal"
                                v-model="importe.subtotal"
                                outlined></VTextField>
                        </VCol>
                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                label="Valor Impuesto"
                                v-model="importe.importe_iva"
                                outlined></VTextField>
                        </VCol>
                    </VRow>

                    <!-- Botones de acciones -->
                    <VDivider style="margin-top: 2rem" />

                    <VRow class="mt-2">
                        <VCol cols="12">
                            <VBtn
                                class="me-2"
                                color="secondary"
                                @click="cancelar">
                                Cancelar
                            </VBtn>
                            <VBtn
                                v-if="!asiento.id"
                                @click="saveAsiento"
                                :disabled="isloading"
                                color="primary"
                                class="white--text"
                                >Guardar</VBtn
                            >

                            <VBtn
                                v-if="asiento.id"
                                @click="saveAsiento"
                                :disabled="isloading"
                                color="success"
                                class="white--text"
                                >Actualizar</VBtn
                            >

                            <VBtn
                                v-if="asiento.id"
                                @click="deleteAsiento"
                                :disabled="isloading"
                                color="red"
                                class="white--text"
                                >Eliminar</VBtn
                            >
                        </VCol>
                    </VRow>
                </VCol>
            </VRow>
        </VCard>
    </div>
</template>

<script>
// import clienteSelectVue from "../../../components/general/clienteSelect.vue";
import multipleSelect from "@/components/multipleSelect.vue";
import {CustomerSearch} from "../../../composables/CustomerSearch";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';

export default {
    mixins: [gestorClienteMixin],
    props: [
        "id_factura",
        "id_cliente",
        "id_proveedor",
        "extra",
        "id_abono",
        "abono",
        "id_factura_entrante",
    ],
    data() {
        return {
            required_val: [(v) => !!v || "El Campo es requerido"],
            numberInput: [(v) => v !== null || "El Campo es requerido"],
            tipo_asiento: [],
            asiento_predefinido: [],
            clientes: [],
            proveedores: [],
            // clientes: [],
            provvedores: [],
            headers_cuentas: [
                {title: "Cuenta", value: "cuenta", sortable: false},
                {title: "Descripción", value: "descripcion", sortable: false},
                {title: "Documento", value: "documento", sortable: false},
                {title: "Debe", value: "debe", sortable: false},
                {title: "Haber", value: "haber", sortable: false},
                {title: "Tags", value: "tag", sortable: false},
                {title: "Acciones", value: "action", sortable: false},
            ],
            cuentas: [],
            // file: null,
            asiento: {
                id: "",
                tipo_apunte_id: null,
                apunte_predefinido_id: null,
                cliente_id: null,
                factura_id: null,
                proveedor_id: null,
                factura_recibida_id: null,
                nota: null,
                documento: null,
                fecha: "",
                cuentas: [],
                importes: [],
            },
            cuenta: {
                id: null,
                cuenta_contable_id: null,
                cuenta: null,
                documento: "",
                descripcion: "",
                tag: "",
                debe: 0,
                haber: 0,
            },
            index: -1,
            menu: false,
            hide_contacto: false,
            // show_clientes: true,
            debeDisabled: false,
            haberDisabled: false,
            iva: 0,
            campos_requeridos: false,
            predefinido: null,
            facturas: [],
            facturas_entrantes: [],
            factura: {},
            factura_id: null,
        };
    },
    watch: {
        id_factura: function (val) {
            this.setupFacturaExterna();
        },
        id_factura_entrante: function (val) {
            this.setupFacturaExterna();
        },
        id_abono: function (val) {
            this.setupFacturaExterna();
        },
        // file: function (newVal, oldVal) {
        //     if(newVal) {
        //         this.createBase64File(newVal);
        //     } else {
        //         this.asiento.documento = null;
        //     }
        // },
        "asiento.cliente_id": function (val) {
            this.getFacturas();
        },
    },
    created() {
        this.init();
        this.getTiposApunte();
        this.getCuentas();
        this.getClientes();
        this.getProveedores();

        this.rol = localStorage.getItem("role");
    },
    methods: {
        onClienteChanged(event) {
            console.log('FormLibroDiario: Cliente cambiado, recargando clientes...', event);
            // Limpiar el cliente seleccionado si existe
            if (this.asiento.cliente_id) {
                this.asiento.cliente_id = null;
            }
            // Recargar la lista de clientes
            this.getClientes();
        },
        async onCustomerSearch(e) {
            const vm = this;
            const customerSearch = CustomerSearch.getInstance();
            customerSearch.debounce(async function () {
                const clientes = await customerSearch.search(e.target.value);
                vm.clientes = clientes;
                console.log(clientes, " busqueda del API");
            }, 800)();
        },
        cancelar() {
            if (this.extra) {
                this.$emit("close");
            } else {
                this.$router.push("/lista-libro-diario");
            }
        },
        setupFacturaExterna() {
            if (this.id_factura != null) {
                this.asiento.factura_id = this.id_factura;
                this.asiento.apunte_predefinido_id = 2;
                this.asiento.tipo_apunte_id = 2;
                this.asiento.cliente_id = this.id_cliente;
                this.asiento.nota = this.extra.nota;
                //this.selectContacto();
                this.setCuentasVentas();
                this.hide_contacto = true;
            }
            if (this.id_factura_entrante != null) {
                this.asiento.factura_recibida_id = this.id_factura_entrante;
                this.asiento.apunte_predefinido_id = 11;
                this.asiento.tipo_apunte_id = 6;
                this.asiento.proveedor_id = this.id_proveedor;
                this.setCuentasCompras();
                this.hide_contacto = true;
            }
        },
        async init() {
            await this.getApuntePredefinido();
            this.setupFacturaExterna();

            if (
                this.$route.query.id &&
                this.id_factura == null &&
                this.id_factura_entrante == null
            ) {
                this.getAsiento(this.$route.query.id);
            }
        },
        // CRUD de cuenta en la tabla
        createCuenta() {
            this.cuenta.cuenta = this.cuentas.find(
                (element) => element.id == this.cuenta.cuenta_contable_id
            ).numero;

            this.asiento.cuentas.push(JSON.parse(JSON.stringify(this.cuenta)));
            let predefinido = this.asiento_predefinido.find(
                (element) => element.id == this.asiento.apunte_predefinido_id
            );

            this.asiento.cuentas.forEach((element) => {
                if (element.cuenta_contable_id == null) {
                    this.campos_requeridos = true;
                } else {
                    this.campos_requeridos = false;
                }
            });
        },
        updateCuentaS(index) {
            console.log(index);
            this.cuenta = JSON.parse(
                JSON.stringify(this.asiento.cuentas[index])
            );
            this.index = index;

            if (this.cuenta.debe > 0) {
                this.debeDisabled = false;
                this.haberDisabled = true;
            } else {
                this.debeDisabled = true;
                this.haberDisabled = false;
            }
        },
        deleteCuenta(indexo) {
            console.log(indexo);
            if (this.asiento.cuentas_eliminadas == null) {
                this.asiento.cuentas_eliminadas = [];
            }
            if (this.asiento.cuentas[indexo].id != null) {
                this.asiento.cuentas_eliminadas.push(
                    this.asiento.cuentas[indexo].id
                );
            }
            this.asiento.cuentas.splice(indexo, 1);
            //console.log(this.cliente);
        },
        updateCuenta() {
            //console.log("prueba");
            this.asiento.cuentas[this.index] = JSON.parse(
                JSON.stringify(this.cuenta)
            );
            this.asiento.cuentas.push({});
            this.asiento.cuentas.pop();
        },

        // Get cuentas contables
        getCuentas() {
            axios.get(`api/get-cuentas`).then(
                (res) => {
                    this.cuentas = res.data.success;
                },
                (res) => {}
            );
        },

        // Get tipos de apunte
        getTiposApunte() {
            axios.get(`api/get-tipos-apunte`).then(
                (res) => {
                    this.tipo_asiento = res.data.success;
                },
                (res) => {}
            );
        },

        // Get tipos de apunte
        async getApuntePredefinido() {
            const res = await axios.get(`api/get-apunte-predefinido`);
            this.asiento_predefinido = res.data.success;
        },

        //CRUD apunte/asiento
        saveAsiento() {
            if (!this.$refs.form.validate()) {
                return;
            }

            axios
                .post("api/save-asiento", this.asiento)
                .then((res) => {
                    if (this.extra != null) {
                        this.$emit("close");
                    } else {
                        this.$router.push("/lista-libro-diario");
                    }
                })
                .catch((error) => {
                    //console.log(error.response.status);
                    if (error.response.code == 400) {
                        $toast.error(error.response.data.error);
                    } else {
                        $toast.error("Algo salio mal");
                    }
                });
        },
        getAsiento(asiento_id) {
            axios.get(`api/get-asiento/${asiento_id}`).then(
                (res) => {
                    this.asiento = res.data.success;

                    this.predefinido = this.asiento_predefinido.find(
                        (element) =>
                            element.id == this.asiento.apunte_predefinido_id
                    );
                    if (
                        this.predefinido.descripcion
                            .toLowerCase()
                            .includes("venta")
                    ) {
                        this.hide_contacto = true;
                        // this.contactos = this.clientes;
                        // this.asiento.contacto_id = this.contactos.find(
                        //     (element) => element.id == this.asiento.contacto_id
                        // );
                    } else if (
                        this.predefinido.descripcion
                            .toLowerCase()
                            .includes("compra")
                    ) {
                        this.hide_contacto = true;
                        // this.contactos = this.clientes;
                        // this.asiento.contacto_id = this.contactos.find(
                        //     (element) => element.id == this.asiento.contacto_id
                        // );
                    } else {
                        // this.asiento.contacto_id = null;
                        this.hide_contacto = false;
                    }
                },
                (res) => {}
            );
        },
        deleteAsiento() {
            axios
                .delete(`api/delete-asiento/${this.$route.query.id}`)
                .then((res) => {
                    this.$router.push("/lista-libro-diario");
                })
                .catch((error) => {
                    if (error.response.code == 400) {
                        $toast.error(error.response.data.error);
                    } else {
                        $toast.error("Algo salio mal");
                    }
                });
        },

        // Seleccionar contacto entre clientes y proveedores
        selectContacto() {
            this.predefinido = this.asiento_predefinido.find(
                (element) => element.id == this.asiento.apunte_predefinido_id
            );

            if (this.predefinido.descripcion.toLowerCase().includes("venta")) {
                this.hide_contacto = true;
                // this.show_clientes = true;
                // this.contactos = this.clientes;
                this.asiento.cliente_id = 0;
                this.debeDisabled = false;
                this.haberDisabled = true;
            } else if (
                this.predefinido.descripcion.toLowerCase().includes("compra")
            ) {
                this.hide_contacto = true;
                // this.show_clientes = false;
                // this.contactos = this.proveedores;
                this.asiento.cliente_id = null;
                this.debeDisabled = true;
                this.haberDisabled = false;
            } else {
                this.asiento.contacto_id = null;
                this.hide_contacto = false;
                this.asiento.cliente_id = null;
            }

            let cuenta_iva;
            if (/\d/.test(this.predefinido.descripcion)) {
                cuenta_iva = this.predefinido.descripcion.match(/\d+/g);
                this.iva = cuenta_iva[0];
            } else {
                this.iva = 21;
            }
        },

        // Get proveedores
        getProveedores() {
            axios
                .get(`api/get-proveedores/${localStorage.getItem("user_id")}`)
                .then(
                    (res) => {
                        this.proveedores = res.data;
                    },
                    (res) => {}
                );
        },

        // Get clientes
        getClientes() {
            axios
                .get(`api/get-clientes`)
                .then(
                    (res) => {
                        this.clientes = res.data.data || res.data;
                    },
                    (err) => {
                        $toast.error("Error consultando clientes");
                    }
                );
        },

        // Obtener las facturas relacionadas a los clientes
        getFacturas() {
            axios
                .get(`api/get-facturas-cliente/${this.asiento.cliente_id}`)
                .then(
                    (res) => {
                        this.facturas = res.data.success;
                    },
                    (res) => {}
                );
        },

        // Obtener las facturas relacionadas a los proveedores
        getFacturasEntrantes() {
            axios
                .get(`api/get-facturas-proveedor/${this.asiento.proveedor_id}`)
                .then(
                    (res) => {
                        this.facturas_entrantes = res.data.success;
                    },
                    (res) => {}
                );
        },

        // Llenar las lineas de la tabla cuentas
        setCuentasVentas() {
            this.asiento.cuentas = [];
            if (this.extra != null) {
                this.factura = this.extra;
            } else {
                this.factura = this.facturas.find(
                    (element) => element.id == this.asiento.factura_id
                );
            }
            console.log("factura", this.factura);
            this.iva = this.factura.iva ?? 0;
            this.asiento.fecha = new Date().toISOString().split("T")[0];
            // const cliente_plata = this.factura.total - this.factura.valor_iva;

            let cuenta2 = {
                id: null,
                cuenta_contable_id: this.factura.cuenta_cliente_id,
                cuenta: this.factura.cuenta_cliente,
                documento: "",
                descripcion: "Venta a cliente",
                tag: "",
                // debe: this.id_abono != null ? 0 : cliente_plata,
                debe: this.factura.subtotal,
                // haber: this.id_abono != null ? this.abono ?? 0 : 0,
                haber: 0,
            };
            this.asiento.cuentas.push(cuenta2);

            if (this.factura.lineas != null) {
                this.factura.lineas.forEach((element) => {
                    let subtotal = element.cantidad * element.precio;

                    let cuenta = {
                        id: null,
                        cuenta_contable_id: element.cuenta_contable_id,
                        cuenta: element.cuenta_producto,
                        documento: "",
                        descripcion: element.descripcion,
                        tag: "",
                        // debe: this.id_abono != null ? element.total : 0,
                        debe: 0,
                        // haber: this.id_abono == null ? element.total : 0,
                        haber: subtotal,
                    };
                    this.asiento.cuentas.push(cuenta);
                });
            }

            let cuenta3 = {
                id: null,
                cuenta_contable_id: this.factura.cuenta_iva_repercutido_id,
                cuenta: this.factura.cuenta_iva_repercutido,
                documento: "",
                descripcion: "Impuesto",
                tag: "",
                // debe: this.id_abono != null ? this.factura.valor_iva : 0,
                debe: 0,
                // haber: this.id_abono == null ? this.factura.valor_iva : 0,
                haber: this.factura.subtotal * this.factura.iva,
            };
            this.asiento.cuentas.push(cuenta3);
        },
        setCuentasCompras() {
            this.asiento.cuentas = [];
            if (this.extra != null) {
                this.factura = this.extra;
            } else {
                this.factura = this.facturas_entrantes.find(
                    (element) => element.id == this.asiento.factura_recibida_id
                );
            }
            console.log("factura", this.factura);

            let cuenta2 = {
                id: null,
                cuenta_contable_id: this.factura.cuenta_proveedor_id,
                cuenta: this.factura.cuenta_proveedor,
                documento: "",
                descripcion: "Compra a proveedor",
                tag: "",
                debe: 0,
                // haber: this.factura.total - this.factura.valor_iva,
                haber: this.factura.subtotal,
            };
            this.asiento.cuentas.push(cuenta2);

            if (this.factura.lineas != null) {
                this.factura.lineas.forEach((element) => {
                    let subtotal = element.cantidad * element.precio;
                    let dcto = (subtotal * element.dcto) / 100;
                    subtotal = subtotal - dcto;

                    let cuenta = {
                        id: null,
                        cuenta_contable_id: element.cuenta_producto_id,
                        cuenta: element.cuenta_producto,
                        documento: "",
                        descripcion: element.concepto,
                        tag: "",
                        debe: subtotal,
                        haber: 0,
                    };
                    this.asiento.cuentas.push(cuenta);

                    if (element.iva != null) {
                        this.factura.cuenta_iva_soportado.forEach(
                            (element2) => {
                                if (element.iva == element2.title) {
                                    let cuenta_impuesto = {
                                        id: null,
                                        cuenta_contable_id: element2.id,
                                        cuenta: element2.cuenta,
                                        documento: "",
                                        descripcion:
                                            "Impuesto " + element.iva + "%",
                                        tag: "",
                                        debe:
                                            (subtotal * parseInt(element.iva)) /
                                            100,
                                        haber: 0,
                                    };
                                    this.asiento.cuentas.push(cuenta_impuesto);
                                }
                            }
                        );
                    }
                });
            }

            /*let cuenta3 = {
        id: null,
        cuenta_contable_id: this.factura.cuenta_iva_soportado_id,
        cuenta: this.factura.cuenta_iva_soportado,
        documento: "",
        descripcion: "Total impuesto",
        tag: "",
        debe: this.factura.valor_iva,
        haber: 0,
      };
      this.asiento.cuentas.push(cuenta3);*/
        },

        //--------------------------------
        subirArchivo64(data) {
            //console.log(extension);
            let me = this;
            axios
                .post(`api/save-archivo64-cliente`, {
                    id: this.cliente.id,
                    archivo: data.data,
                    nombre: data.nombre,
                    extension: data.extension,
                })
                .then((res) => {
                    me.cliente.archivo = res.data;
                    $toast.sucs("Guardado correctamente");
                    clear();
                });
        },
        // Convertir el file en base 64
        // createBase64File: function(FileObject) {
        //     const reader = new FileReader();
        //     reader.onload = (event) => {
        //         this.asiento.documento = event.target.result;
        //     }
        //     reader.readAsDataURL(FileObject);
        // },
        error(err) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
        },
        subirArchivo(archivo) {
            archivo.append("id", this.cliente.id);
            axios.post(`api/save-archivo-cliente`, archivo).then((res) => {
                this.cliente.archivo = res.data;
            });
        },

        deleteArchivo(archivo) {
            axios.get(`api/deletearchivo/${archivo.id}`).then(
                (res) => {
                    this.cliente.archivo = res.data;
                },
                (res) => {
                    $toast.error("Error al eliminar el archivo");
                }
            );
        },
        Validar() {
            axios
                .post("api/validate-cliente", {id: this.$route.query.id})
                .then((res) => {
                    this.$router.go();
                });
        },
    },
    filters: {},

    computed: {
        isloading() {
            return this.$store.getters.getloading;
        },
        subtotal() {
            let subtotal = 0;
            if (this.factura.subtotal != null) {
                subtotal = this.factura.subtotal;
            } else {
            }
            return subtotal;
        },
        valor_iva() {
            let iva = 0;
            if (this.factura.valor_iva != null) {
                iva = this.factura.valor_iva;
            } else {
            }
            return iva;
        },
    },
    components: {
        // clienteSelectVue,
        multipleSelect,
    },
};
</script>
<style>
.rowtwo {
    display: flex;
    flex-direction: row;
}
</style>
