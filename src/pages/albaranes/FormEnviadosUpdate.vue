<template>
    <VCard
        class="pb-5"
        title="Editar Albarán Enviado">
        <VDivider></VDivider>

        <loader v-if="isloading"></loader>

        <VCardText>
            <VRow>
                <VCol
                    cols="12"
                    sm="12"
                    md="6"
                    lg="6"
                    xl="6">
                    <VSelect
                        label="Cliente"
                        v-model="form.cliente"
                        :items="clientes"
                        item-title="nombre"
                        @input="onCustomerSearch"
                        return-object></VSelect>
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
                    depressed
                    class="white--text mb-3 ml-4 mt-1"
                    @click="addAenviado()"
                    >Agregar</VBtn
                >
            </VRow>
            <VRow v-if="arrayAgregados.length > 0">
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
                            {{ formatPrice(parseEuroNumber(item.precio)) }}
                        </template>
                        <template v-slot:item.importe="{item}">
                            {{ formatPrice(parseEuroNumber(item.importe)) }}
                        </template>

                        <template v-slot:item.action="{item}">
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
                        @change="changeIva($event.target.value)"
                        label="Incluir Iva"></VCheckbox>
                    <VCheckbox
                        v-model="descuentoIncluir"
                        @change="changeCheckboxDescuento($event.target.value)"
                        label="Incluir Descuento"></VCheckbox>
                    <VRow class="mt-2">
                        <VCol
                            cols="12"
                            sm="12"
                            md="4"
                            lg="4">
                            <VTextField
                                v-if="descuentoIncluir"
                                @change="changeDescuento($event.target.value)"
                                label="Descuento %"
                                v-model="descuento_porcent"></VTextField>
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
                        v-if="descuentoIncluir">
                        <strong
                            >Descuento: {{ formatPrice(descuento) }}€</strong
                        >
                    </p>
                    <p
                        style="text-align: right"
                        v-if="ivaIncluir">
                        <strong>Iva 21%: {{ formatPrice(montoIva) }}€</strong>
                    </p>
                    <p style="text-align: right; margin-top: 25px">
                        <strong>TOTAL : {{ formatPrice(total) }}€</strong>
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
                        :to="{ name: 'lista-notas' }">
                        Volver
                    </VBtn>
                    <template
                        v-if="
                            arrayAgregados.length > 0 && visualizador != false
                        ">
                        <VBtn
                            rounded="pill"
                            class="mx-1"
                            @click="guardar()"
                            v-if="
                                formShow.contabilizado == null &&
                                convertidoANotaOFactura == false
                            "
                            >Actualizar
                        </VBtn>
                        <VBtn
                            rounded="pill"
                            variant="outlined"
                            class="mx-1"
                            target="_blank"
                            :href="
                                '/storage/albaranes/enviados/userId_' +
                                this.recibo.user_id +
                                '/' +
                                urlFactura
                            "
                            >Ver PDF Albarán
                        </VBtn>
                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            @click="choiseConvertirFactura"
                            v-if="
                                urlFactura != false &&
                                generar_factura == false &&
                                formShow.contabilizado == null &&
                                convertidoANotaOFactura == false
                            ">
                            Generar Factura
                        </VBtn>
                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            @click="facturaConfirm"
                            v-if="
                                urlFactura != false &&
                                generar_factura == true &&
                                formShow.contabilizado == null &&
                                convertidoANotaOFactura == false &&
                                !isEmpleado
                            ">
                            Convertir a Factura
                        </VBtn>
                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="
                                factura_generada != false &&
                                formShow.contabilizado == null
                            "
                            target="_blank"
                            :href="
                                '/storage/recibos/userId_' +
                                this.recibo.user_id +
                                '/' +
                                factura_generada
                            ">
                            Ver Factura
                        </VBtn>
                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            @click="notaConfirm"
                            v-if="
                                urlFactura != false &&
                                formShow.contabilizado == null &&
                                convertidoANotaOFactura == false
                            ">
                            Convertir a Nota
                        </VBtn>
                        <VBtn
                            rounded="pill"
                            variant="tonal"
                            class="mx-1"
                            v-if="
                                nota_generada != false &&
                                formShow.contabilizado == null
                            "
                            target="_blank"
                            :href="'/storage/recibos/' + nota_generada">
                            Ver Nota
                        </VBtn>
                    </template>
                </VCol>
            </VRow>
        </VCardText>

        <modal-confirm
            :convertirNota="convertirNota"
            :convertirFactura="convertirFactura"
            :itemPdf="itemPdf"
            :modalConfirm="modalConfirm"
            :closeModalConfirmFactura="closeModalConfirmFactura"
            color="primary">
        </modal-confirm>
    </VCard>
</template>
<script type="text/javascript">
import Confirmar from "./Confirmar.vue";
import {CustomerSearch} from "../../composables/CustomerSearch";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';

export default {
    components: {
        "modal-confirm": Confirmar,
    },
    mixins: [gestorClienteMixin],
    data() {
        return {
            descuento_porcent: 0,
            albaranGloalId: "",
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
                cliente: "",
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
                user_id: "9",
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
                user_id: localStorage.getItem("user_id"),
            },
            factura_generada: false,
            nota_generada: false,
            errorCantidad: "",
            errorPrecio: "",
            albaranId: "",
            modalConfirm: false,
            itemPdf: "",
            formShow: {},
            cadena: "",
            metodoGuardar: false,
            convertidoANotaOFactura: false,
        };
    },
    watch: {
        "form.cantidad"(n) {
            this.form.importe = formatPrice(
                parseEuroNumber(n) * parseEuroNumber(this.form.precio)
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
        descuento_porcent(n) {
            this.changeDescuento(n);
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        errors() {
            return this.$store.getters.geterrors;
        },
        userId() {
            return localStorage.user_id;
        },
        esContabilizadoPor() {
            let extraida = this.formShow.contabilizado;
            return extraida;
        },
        isEmpleado() {
            const role = parseInt(localStorage.getItem('role'));
            return role === 4;
        },
    },
    created() {
        this.getClientes();
        // Obtener el ID del albarán desde los parámetros de la ruta
        const albaranId = this.$route.params.idAlbaran;
        if (albaranId) {
            this.getAlbaranById(albaranId);
            this.albaranGloalId = albaranId;
        } else {
            $toast.error("No se encontró el ID del albarán");
        }
    },
    methods: {
        onClienteChanged(event) {
            console.log('FormEnviadosUpdate: Cliente cambiado, recargando clientes...', event);
            // Limpiar el cliente seleccionado si existe
            if (this.form.cliente) {
                this.form.cliente = null;
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
        getAlbaranById(albaranId) {
            axios
                .get(
                    `api/get-albaranes-enviados-show/` +
                        albaranId +
                        "?existente=" +
                        false
                )
                .then(
                    (res) => {
                        this.formShow = res.data.albaranEnviado;
                        this.form.cliente = res.data.albaranEnviado.cliente;
                        this.form.fecha = res.data.albaranEnviado.fecha;
                        this.form.cantidad = 0;
                        this.form.precio = 0;
                        this.form.importe = 0;
                        this.urlFactura = res.data.albaranEnviado.url;
                        this.selectedTemplate =
                            res.data.albaranEnviado.template ||
                            this.selectedTemplate ||
                            "classic";
                        let jsonAlbaran = res.data.albaranEnviado.item_albaran;
                        this.arrayAgregados = jsonAlbaran;
                        this.calculoTotal();
                        this.albaranId = res.data.albaranEnviado.id;
                    },
                    (res) => {
                        $toast.error("Error consultando Proveedores");
                    }
                );
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
                this.descuento = (this.subtotal * value) / 100;
                this.montoIva = (this.subtotal - this.descuento) * 0.21;
                this.total = this.subtotal - this.descuento + this.montoIva;
            } else {
                this.errorPrecio = "Inserte un numero entero o decimal";
                this.descuento = 0;
                return;
            }
            /*this.calculoTotal()
        if(this.descuentoIncluir == true){
          if(this.montoIva != 0){
            this.changeIva(true)
          }
          this.total = parseFloat(1*this.total -  value).toFixed(2)
        }else{                   
          this.changeIva(true)
        }   */
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
                factura_url: this.urlFactura,
                nota_url: null,
                has_iva: enviarIva,
                servicios: this.arrayAgregados,
                user_id: localStorage.getItem("user_id"),
            };
            axios.post(`api/save-factura-albaran`, commit).then(
                (res) => {
                    this.visualizador = true;
                    this.factura_generada = res.data.data.recibo.factura_url;
                    this.convertidoANotaOFactura = true;
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
                user_id: localStorage.getItem("user_id"),
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
            
            this.metodoGuardar = true;
            this.visualizador = false;
            let formData = new FormData();
            formData.append("enviados", JSON.stringify(itemsConCliente));
            formData.append("fecha_emision", this.form.fecha);
            formData.append("cliente_id", this.form.cliente.id);
            formData.append("template", this.selectedTemplate || "classic");
            axios
                .post(
                    "api/update-albaran-enviados/" + this.albaranGloalId,
                    formData
                )
                .then(
                    (res) => {
                        this.urlFactura = res.data.albaran.url;
                        this.generar_factura = false;
                        $toast.sucs("Albaran guardado con exito");
                        this.visualizador = true;
                        this.albaranId = res.data.albaran.id;
                        
                        // Emitir evento para recargar la lista cuando se navegue de vuelta
                        window.dispatchEvent(new CustomEvent('albaran-guardado'));
                    },
                    (res) => {
                        $toast.error(res.response?.data?.message || "Error guardando albarán");
                        this.visualizador = true;
                    }
                );
        },
        deleteEnvi(item) {
            const indice = this.arrayAgregados.indexOf(item);
            this.arrayAgregados.splice(indice, 1);
            this.calculoTotal();
        },
        addAenviado() {
            this.arrayAgregados.push(this.form);
            this.form = {
                cliente: this.form.cliente,
                fecha: this.form.fecha,
                descripcion: "",
                cantidad: 0,
                precio: 0,
                importe: 0,
            };
            this.calculoTotal();
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
        validateCantidad(valor) {
            this.form.cantidad = "";

            var RE = /^\d*\.?\d*$/;
            if (RE.test(valor)) {
                this.errorCantidad = "";
                this.form.cantidad = valor;
            } else {
                this.errorCantidad = "Inserte un numero entero ";
                this.form.cantidad = 0;
            }
        },
        validatePrecio(valor) {
            this.form.precio = "";
            var RE = /^\d*\.?\d*$/;
            if (RE.test(valor)) {
                this.errorPrecio = "";
                this.form.precio = valor;
            } else {
                this.errorPrecio = "Inserte un numero entero o decimal";
                this.form.precio = 0;
            }
        },
        validateDescuento(valor) {
            this.descuento = 0;
            var RE = /^\d*\.?\d*$/;
            if (RE.test(valor)) {
                this.errorPrecio = "";
                this.descuento = 1 * valor;
            } else {
                this.errorPrecio = "Inserte un numero entero o decimal";
                this.descuento = 0;
            }
        },
    },
};
</script>
