<template>
    <VCard
        class="pb-5"
        title="Crear Albarán Enviado">
        <VDivider></VDivider>

        <loader v-if="isloading"></loader>

        <div class="pa-5">
            <VRow>
                <VCol
                    cols="12"
                    sm="12"
                    md="6"
                    lg="6"
                    xl="6">
                    <VAutocomplete
                        label="Cliente"
                        v-model="form.cliente"
                        :items="clientes"
                        item-title="nombre"
                        @input="onCustomerSearch"
                        return-object></VAutocomplete>
                </VCol>

                <VCol
                    cols="12"
                    sm="12"
                    md="6"
                    lg="6"
                    xl="6">
                    <AppDateTimePicker
                        v-model="form.fecha"
                        first-day-of-week="1"
                        label="Fecha"
                        prepend-icon="ri-calendar-fill"
                        :error-messages="
                            errors.errors.fecha ? errors.errors.fecha[0] : null
                        " />
                </VCol>
            </VRow>
            <VRow>
                <VCol
                    cols="12"
                    sm="12"
                    md="6"
                    lg="6"
                    xl="6">
                </VCol>
            </VRow>

            <VRow>
                <VCol
                    cols="12"
                    sm="12"
                    md="4"
                    lg="4"
                    xl="4">
                    <VTextField
                        label="Descripción"
                        v-model="form.descripcion"></VTextField>
                </VCol>

                <VCol
                    cols="12"
                    sm="12"
                    md="2"
                    lg="2"
                    xl="2">
                    <VTextField
                        label="Cantidad"
                        :rules="[integerValidator]"
                        v-model="form.cantidad"></VTextField>
                </VCol>

                <VCol
                    cols="12"
                    sm="12"
                    md="3"
                    lg="3"
                    xl="3">
                    <VTextField
                        label="Precio"
                        @input="form.precio = inputPrice(form.precio)"
                        v-model="form.precio"></VTextField>
                </VCol>

                <VCol
                    cols="12"
                    sm="12"
                    md="3"
                    lg="3"
                    xl="3">
                    <VTextField
                        label="Importe"
                        v-model="form.importe"
                        readonly></VTextField>
                </VCol>

                <VBtn
                    rounded="pill"
                    class="ma-3"
                    @click="addAenviado()"
                    >Agregar</VBtn
                >
            </VRow>

            <VRow>
                <VCol
                    cols="12"
                    md="12">
                    <VDataTable
                        :headers="headers"
                        :items="arrayAgregados"
                        disable-pagination
                        hide-default-footer
                        item-key="id"
                        class="elevation-1">
                        <template v-slot:item.precio="{item}">
                            {{ formatPrice(item.precio) }}
                        </template>
                        <template v-slot:item.action="{item}">
                            <VIcon
                                @click="setItem(item)"
                                small
                                class="mr-2"
                                color="blue"
                                >ri-pencil-line</VIcon
                            >
                            <VIcon
                                @click="deleteEnvi(item)"
                                small
                                class="mr-2"
                                color="red"
                                >ri-delete-bin-line</VIcon
                            >
                        </template>
                    </VDataTable>
                </VCol>
            </VRow>

            <VRow v-if="seleccionConvertirFactura">
                <VCol
                    cols="12"
                    sm="12"
                    md="6"
                    lg="6"
                    xl="6">
                    <VCheckbox
                        v-model="ivaIncluir"
                        @change="changeIva"
                        label="Incluir Iva"></VCheckbox>

                    <VCheckbox
                        v-model="descuentoIncluir"
                        @change="changeCheckboxDescuento"
                        label="Incluir Descuento"></VCheckbox>

                    <VRow>
                        <VCol
                            cols="12"
                            sm="12"
                            md="4"
                            lg="4">
                            <VTextField
                                v-if="descuentoIncluir"
                                @change="changeDescuento"
                                label="Descuento %"
                                v-model="descuento"></VTextField>
                        </VCol>
                    </VRow>
                </VCol>

                <VCol
                    cols="12"
                    sm="12"
                    md="6"
                    lg="6"
                    xl="6">
                    <p style="text-align: right">
                        <strong>Subtotal: {{ formatPrice(subtotal) }}€</strong>
                    </p>
                    <p
                        style="text-align: right"
                        v-if="ivaIncluir">
                        <strong>Iva 21%: {{ formatPrice(montoIva) }}€</strong>
                    </p>
                    <p
                        style="text-align: right"
                        v-if="descuentoIncluir && ivaIncluir">
                        <strong
                            >Descuento: {{ formatPrice(descuento) }}€</strong
                        >
                    </p>
                </VCol>
            </VRow>

            <VRow>
                <VCol
                    cols="12"
                    md="12">
                    <p style="text-align: right">
                        <strong>Total : {{ formatPrice(total) }}€</strong>
                    </p>
                </VCol>
            </VRow>

            <VRow>
                <VProgressLinear
                    v-if="!visualizador"
                    indeterminate
                    color="yellow darken-2"></VProgressLinear>
            </VRow>

            <VRow>
                <VCol
                    cols="12"
                    sm="12"
                    md="12"
                    lg="12">
                    <VBtn
                        rounded="pill"
                        variant="outlined"
                        color="secondary"
                        class="mr-1"
                        :to="{ name: 'lista-notas' }"
                        >Volver
                    </VBtn>

                    <template
                        v-if="
                            arrayAgregados.length > 0 && visualizador != false
                        ">
                        <VBtn
                            rounded="pill"
                            class="mx-1"
                            v-if="convertidoANotaOFactura == false"
                            @click="guardar()"
                            >Guardar y generar pdf
                        </VBtn>

                        <VBtn
                            rounded="pill"
                            variant="outlined"
                            class="mx-1"
                            v-if="urlFactura != false"
                            target="_blank"
                            :href="
                                '/storage/albaranes/enviados/userId_' +
                                storageOwnerId +
                                '/' +
                                urlFactura
                            "
                            >Ver PDF Albarán
                        </VBtn>

                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="
                                urlFactura != false &&
                                generar_factura == false &&
                                convertidoANotaOFactura == false
                            "
                            @click="choiseConvertirFactura"
                            >Generar Factura
                        </VBtn>

                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="
                                urlFactura != false &&
                                generar_factura == true &&
                                convertidoANotaOFactura == false &&
                                !isEmpleado
                            "
                            @click="facturaConfirm"
                            >Convertir a Factura
                        </VBtn>

                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="factura_generada != false"
                            target="_blank"
                            :href="
                                '/storage/recibos/userId_' +
                                storageOwnerId +
                                '/' +
                                factura_generada
                            ">
                            Ver Factura
                        </VBtn>

                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="
                                urlFactura != false &&
                                convertidoANotaOFactura == false
                            "
                            @click="notaConfirm"
                            >Convertir a Nota
                        </VBtn>

                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="nota_generada != false"
                            target="_blank"
                            :href="'/storage/recibos/' + nota_generada">
                            Ver Nota
                        </VBtn>
                    </template>
                </VCol>
            </VRow>
        </div>
    </VCard>

    <modal-confirm
        :itemPdf="itemPdf"
        :modalConfirm="modalConfirm"
        :closeModalConfirmFactura="closeModalConfirmFactura"
        :convertirFactura="convertirFactura"
        :convertirNota="convertirNota"
        color="primary">
    </modal-confirm>
</template>

<script>
import Confirmar from "./Confirmar.vue";
import {CustomerSearch} from "../../composables/CustomerSearch";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import { effectiveBusinessUserId } from "@/utils/tenantContext";

export default {
    components: {
        "modal-confirm": Confirmar,
    },
    mixins: [gestorClienteMixin],

    data() {
        return {
            subtotal: 0,
            generar_factura: false,
            descuento: 0,
            seleccionConvertirFactura: false,
            ivaIncluir: true,
            descuentoIncluir: false,
            montoIva: 0,
            availableTemplates: [
                {label: "Clásico", value: "classic"},
                {label: "Moderno", value: "modern"},
            ],
            selectedTemplate: "classic",
            templateLoading: false,
            headers: [
                {title: "Descripción", align: "left", value: "descripcion"},
                {title: "Cantidad", value: "cantidad"},
                {title: "Precio", value: "precio"},
                {title: "Importe", value: "importe"},
                {title: "Acciones", value: "action", sortable: false},
            ],
            menu: false,
            form: {
                id: `temp-${new Date().getTime()}`,
                cliente: null,
                fecha: new Date().toISOString().substr(0, 10),
                descripcion: "",
                cantidad: 0,
                precio: 0,
                importe: 0,
            },
            total: 0,
            arrayAgregados: [],
            clientes: [],
            visualizador: true,
            urlFactura: false,
            fecha_emision: new Date().toISOString().substr(0, 10),
            servicios: {
                cantidad: 1,
                descripcion: "dwqdwq",
                id: "temp-1626799959740",
                importe: 0,
                precio: 0,
                recibo_id: null,
            },
            recibo: {
                cliente_id: "",
                cliente: {
                    id: "",
                    email: null,
                },
                fecha: new Date().toISOString().substr(0, 10),
                sub_total: 0,
                iva: 0,
                porcentaje_descuento: 0,
                total_descuento: 0,
                total: 0,
                factura_url: null,
                nota_url: null,
                has_iva: true,
                servicios: [],
            },
            factura_generada: false,
            nota_generada: false,
            errorCantidad: "",
            errorPrecio: "",
            albaranId: "",
            modalConfirm: false,
            itemPdf: "",
            convertidoANotaOFactura: false,
        };
    },

    watch: {
        "form.cantidad"(n) {
            this.form.importe = formatPrice(
                n * parseEuroNumber(this.form.precio)
            );
        },

        "form.precio"(n) {
            this.form.importe = formatPrice(
                parseEuroNumber(n) * this.form.cantidad
            );
        },

        "recibo.servicios"(n) {
            if (n.length > 0) {
                this.calcularTotales(n);
            }
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        errors() {
            return this.$store.getters.geterrors;
        },
        storageOwnerId() {
            const id = this.recibo?.user_id;
            return id || effectiveBusinessUserId();
        },
        isEmpleado() {
            const role = parseInt(localStorage.getItem('role'));
            return role === 4;
        },
    },

    created() {
        this.getClientes();
    },

    methods: {
        onClienteChanged(event) {
            console.log('FormAlbaranesEnviados: Cliente cambiado, recargando clientes...', event);
            // Limpiar el cliente seleccionado si existe
            if (this.form.cliente) {
                this.form.cliente = null;
            }
            // Recargar la lista de clientes
            this.getClientes();
        },
        closeModalConfirmFactura() {
            this.modalConfirm = false;
            this.itemPdf = "";
        },
        facturaConfirm() {
            this.modalConfirm = true;
            this.itemPdf = "Factura";
        },
        notaConfirm() {
            this.modalConfirm = true;
            this.itemPdf = "Nota";
        },
        changeCheckboxDescuento(value) {
            if (value == false) {
                this.calculoTotal();
                if (this.montoIva != 0) {
                    this.changeIva(true);
                }
            } else {
                if (this.montoIva != 0) {
                    this.changeIva(true);
                }
                this.total = this.total - this.descuento;
            }
        },
        changeDescuento(value) {
            var RE = /^\d*\.?\d*$/;
            if (RE.test(value)) {
                this.descuento = 0;
                this.errorPrecio = "";
                this.descuento = 1 * value;
            } else {
                this.errorPrecio = "Inserte un numero entero o decimal";
                this.descuento = 0;
                return;
            }
            this.calculoTotal();
            if (this.descuentoIncluir == true) {
                if (this.montoIva != 0) {
                    this.changeIva(true);
                }
                this.total = parseFloat(1 * this.total - value).toFixed(2);
            } else {
                this.changeIva(true);
            }
        },
        changeIva(value) {
            this.calculoTotal();
            let vm = this.total;
            if (value == true) {
                this.montoIva = (vm * 21) / 100;
                this.total = 1 * this.total + 1 * this.montoIva;
                console.log(this.total);
            } else {
                this.calculoTotal();
                this.montoIva = 0;
            }
        },

        choiseConvertirFactura() {
            this.seleccionConvertirFactura = true;
            this.generar_factura = true;
            this.changeIva(true);
        },

        convertirFactura() {
            this.modalConfirm = false;
            this.visualizador = false;
            let tipo = "factura";
            let convertir_factura = true;
            let idClient = this.form.cliente.id;
            let calculoDescuento = 0;
            let enviarIva = false;
            if (this.descuentoIncluir == true) {
                calculoDescuento = this.descuento;
            }
            if (this.ivaIncluir == true) {
                enviarIva = true;
            }
            let commit = {
                albaran_id: this.albaranId,
                cliente_id: idClient,
                fecha: new Date().toISOString().substr(0, 10),
                sub_total: this.subtotal,
                iva: 1 * this.montoIva,
                porcentaje_descuento: 0,
                total_descuento: 1 * calculoDescuento,
                total: 1 * this.total,
                factura_url: null,
                nota_url: null,
                has_iva: enviarIva,
                servicios: this.arrayAgregados,
            };
            axios.post(`api/save-factura-albaran`, commit).then(
                (res) => {
                    this.visualizador = true;
                    this.convertidoANotaOFactura = true;
                    this.factura_generada = res.data.data.recibo.factura_url;
                },
                (res) => {
                    $toast.error("Error guardando recibo");
                }
            );
        },

        convertirNota() {
            this.modalConfirm = false;
            this.visualizador = false;
            let tipo = "nota";
            let convertir_factura = true;
            let idClient = this.form.cliente.id;
            let calculoDescuento = 0;
            let enviarIva = false;
            if (this.descuentoIncluir == true) {
                calculoDescuento = this.descuento;
            }
            if (this.ivaIncluir == true) {
                enviarIva = true;
            }
            let commit = {
                albaran_id: this.albaranId,
                cliente_id: idClient,
                fecha: new Date().toISOString().substr(0, 10),
                sub_total: this.subtotal,
                iva: 1 * this.montoIva,
                porcentaje_descuento: 0,
                total_descuento: 1 * calculoDescuento,
                total: 1 * this.total,
                factura_url: null,
                nota_url: null,
                has_iva: enviarIva,
                servicios: this.arrayAgregados,
            };
            axios.post(`api/save-nota-albaran`, commit).then(
                (res) => {
                    this.visualizador = true;
                    this.convertidoANotaOFactura = true;
                    this.nota_generada = res.data.data.recibo.nota_url;
                },
                (res) => {
                    $toast.error("Error guardando recibo");
                }
            );
        },

        guardar() {
            // Validar que haya un cliente seleccionado
            if (!this.form.cliente || !this.form.cliente.id) {
                $toast.error("Debe seleccionar un cliente");
                return;
            }
            
            // Validar que haya items en el array
            if (this.arrayAgregados.length === 0) {
                $toast.error("Debe agregar al menos un item al albarán");
                return;
            }
            
            // Asegurar que cada item tenga el cliente
            const itemsConCliente = this.arrayAgregados.map(item => ({
                ...item,
                cliente: this.form.cliente
            }));
            
            this.visualizador = false;
            let formData = new FormData();
            formData.append("enviados", JSON.stringify(itemsConCliente));
            formData.append("fecha_emision", this.fecha_emision);
            formData.append("template", this.selectedTemplate || "classic");

            axios
                .post("api/save-albaran-enviado", formData)
                .then((res) => {
                    this.urlFactura = res.data.albaran.url;
                    this.generar_factura = false;
                    $toast.sucs("Albarán guardado con éxito");
                    this.visualizador = true;
                    this.albaranId = res.data.albaran.id;
                    
                    // Emitir evento para recargar la lista cuando se navegue de vuelta
                    window.dispatchEvent(new CustomEvent('albaran-guardado'));
                })
                .catch((err) => {
                    $toast.error(err.response?.data?.message || "Error al guardar albarán");
                    this.visualizador = true;
                });
        },

        deleteEnvi(item) {
            const indice = this.arrayAgregados.indexOf(item);
            this.arrayAgregados.splice(indice, 1);
            this.calculoTotal();
        },

        resetAlbaranForm() {
            this.form = {
                id: `temp-${new Date().getTime()}`,
                cliente: this.form.cliente,
                fecha: this.form.fecha,
                descripcion: "",
                cantidad: 0,
                precio: 0,
                importe: 0,
            };
        },

        addAenviado() {
            if (
                this.form.descripcion != "" &&
                this.form.cantidad != "" &&
                this.form.precio != ""
            ) {
                let index = this.arrayAgregados.findIndex(
                    (element) => element.id == this.form.id
                );

                if (index > -1) {
                    this.calculoTotal();
                    this.resetAlbaranForm();
                    return null;
                }

                this.arrayAgregados.push(this.form);
                this.calculoTotal();
                this.resetAlbaranForm();
            } else {
                $toast.error(
                    "Introduzca: DESCRIPCION, CANTIDAD y PRECIO CORRECTOS"
                );
            }
        },

        calculoTotal() {
            this.total = 0;
            this.subtotal = 0;

            for (var i = 0; i < this.arrayAgregados.length; i++) {
                this.total =
                    1 * this.total +
                    1 * parseEuroNumber(this.arrayAgregados[i].importe);

                this.subtotal =
                    1 * this.subtotal +
                    1 * parseEuroNumber(this.arrayAgregados[i].importe);
            }
        },

        getClientes() {
            axios
                .get(`api/get-clientes`)
                .then(
                    (res) => {
                        this.clientes = res.data.data || res.data;
                    },
                    (res) => {
                        $toast.error("Error consultando Clientes");
                    }
                );
        },

        validateCantidad() {
            var RE = /^[0-9]{1,}$/;

            if (RE.test(this.form.cantidad)) {
                this.errorCantidad = "";
            } else {
                this.errorCantidad = "Inserte un numero entero ";
                this.form.cantidad = 0;
            }
        },

        validatePrecio() {
            var RE = /^\d*\.?\d*$/;

            if (RE.test(this.form.precio)) {
                this.errorPrecio = "";
            } else {
                this.errorPrecio = "Inserte un numero entero o decimal";
                this.form.precio = 0;
            }
        },

        setItem(item) {
            this.form = item;
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
    },
};
</script>
