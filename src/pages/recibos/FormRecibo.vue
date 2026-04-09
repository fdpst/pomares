<template>
    <loader v-if="isloading"></loader>

    <VCard :title="title">
        <VDivider></VDivider>

        <!-- <VContainer>
      <VBtn
        :to="path_volver"
        rounded="pill"
        color="secondary"
        class="white--text mt-3"
      >
        volver
      </VBtn> 
    </VContainer> -->

        <VCardText>
            <VForm ref="form" v-model="validForm">
                <!-- datos del cliente y fecha -->
                <VRow>
                    <VCol cols="12" md="12">
                        <p><strong>DATOS DEL CLIENTE Y FECHA</strong></p>
                    </VCol>

                    <VCol cols="12" md="1">
                        <VTextField
                            hide-details
                            filled
                            v-model="document_number"
                            :label="labelByType[tipo]"
                        >
                        </VTextField>
                    </VCol>
                    <VCol
                        v-if="
                            tipo != 'nota' &&
                            tipo != 'presupuesto' &&
                            tipo != 'parte-trabajo'
                        "
                        cols="12"
                        md="2"
                    >
                        <CRUDSelect
                            v-model="recibo.serie_id"
                            title="Serie"
                            :items="series"
                            item-title="serie"
                            item-value="id"
                            label="Series"
                            placeholder="Selecciona una serie"
                            clearable
                            @submit="submitSeries"
                            @update:selectedItem="onEditSerie"
                            @delete="onDeleteSerie"
                        >
                            <template #form>
                                <VCol cols="12">
                                    <VTextField
                                        v-model="form_series.serie"
                                        label="Serie"
                                        :rules="[requiredValidator]"
                                    />
                                </VCol>
                            </template>
                        </CRUDSelect>
                    </VCol>

                    <VCol cols="12" md="3">
                        <!-- <VAutocomplete
                            filled
                            v-model="recibo.cliente_id"
                            :error-messages="
                                errors.errors.cliente_id
                                    ? errors.errors.cliente_id[0]
                                    : null
                            "
                            @input="onChangeCustomer"
                            :items="clientes"
                            item-title="nombre"
                            item-value="id"
                            label="Cliente"
                            :rules="[requiredValidator]"
                        >
                        </VAutocomplete> -->
                        <ClienteSelect
                            v-model="recibo.cliente_id"
                            label="Cliente"
                            itemTitle="nombre"
                            variant="outlined"
                            clearable
                            :extra="selectedCustomer"
                        />
                    </VCol>

                    <VCol cols="12" md="2">
                        <AppDateTimePicker
                            v-model="recibo.fecha"
                            label="Fecha"
                            prepend-icon="ri-calendar-fill"
                            :error-messages="
                                errors.errors.fecha
                                    ? errors.errors.fecha[0]
                                    : null
                            "
                        />
                    </VCol>
                    <VCol
                        cols="12"
                        md="2"
                        v-if="tipo != 'presupuesto' && tipo != 'nota'"
                    >
                        <AppDateTimePicker
                            v-model="recibo.fecha_pago"
                            label="Fecha pago"
                            prepend-icon="ri-calendar-fill"
                        />
                    </VCol>
                    <VCol cols="12" md="2" v-if="tipo == 'factura'">
                        <VCheckbox
                            v-model="recibo.pagado"
                            label="Pagada"
                        ></VCheckbox>
                    </VCol>

                    <VCol cols="12">
                        <VExpansionPanels variant="accordion">
                            <VExpansionPanel title="Datos del cliente">
                                <VExpansionPanelText>
                                    <CustomerForm
                                        :key="`customer-form-${
                                            recibo.cliente_id || 'new'
                                        }`"
                                        @update:customer-list="
                                            onUpdateCustomerList
                                        "
                                        :data="selectedCustomer"
                                    />
                                </VExpansionPanelText>
                            </VExpansionPanel>
                        </VExpansionPanels>
                    </VCol>

                    <!--VCol cols="12" md="3" v-if="tipo == 'presupuesto'">
            <AppDateTimePicker
              v-model="recibo.fecha_tope"
              label="Fecha tope"
              prepend-icon="ri-calendar-fill"
            />
          </VCol-->

                    

                    

                   <!-- <VCol cols="12" md="3" v-if="tipo == 'factura'">
                        <VCheckbox
                            v-model="recibo.recurrente"
                            label="Recurrente"
                        ></VCheckbox>
                    </VCol>-

                    <VCol cols="12" md="3" v-if="tipo == 'factura'">
                        <VTextField
                            v-if="recibo.recurrente"
                            v-model="recibo.fecha_recurrente"
                            label="Fecha de recurrencia"
                            prepend-icon="ri-calendar-fill"
                            type="number"
                            min="1"
                            max="31"
                        />
                    </VCol>-->
                </VRow>
                <!--/ datos del cliente y fecha -->

                <!-- datos del servicio -->
                <VRow dense class="mt-5">
                    <VCol cols="12" md="12">
                        <p
                            v-if="
                                tipo == 'factura' || tipo == 'facturaproforma'
                            "
                        >
                            <strong>DATOS DEL SERVICIO A FACTURAR</strong>
                        </p>
                        <p v-else><strong>DATOS DEL SERVICIO</strong></p>
                    </VCol>

                    <VCol v-if="batchEnabled" cols="12" md="1">
                        <VTextField
                            hide-details
                            filled
                            v-model="servicio.lote"
                            label="Lote"
                        ></VTextField>
                    </VCol>

                    <VCol cols="12" md="2">
                        <VAutocomplete
                            hide-details
                            filled
                            :items="servicios"
                            v-model="servicio.id_servicio"
                            item-title="descripcion"
                            item-value="id"
                            label="Artículo Venta"
                        ></VAutocomplete>
                    </VCol>

                    <VCol cols="12" md="3">
                        <VTextField
                            hide-details
                            filled
                            v-model="servicio.descripcion"
                            label="Descripción"
                        ></VTextField>
                    </VCol>

                    <VCol cols="12" md="1">
                        <VTextField
                            hide-details
                            filled
                            type="number"
                            v-model="servicio.cantidad"
                            label="Cantidad"
                            :rules="[integerValidator]"
                        ></VTextField>
                    </VCol>

                    <VCol cols="12" md="1">
                        <VTextField
                            hide-details
                            filled
                            v-model="servicio.precio"
                            label="Precio"
                            @input="
                                servicio.precio = inputPrice(servicio.precio)
                            "
                        ></VTextField>
                    </VCol>

                    <VCol
                        v-if="tipo != 'nota' && tipo != 'presupuesto'"
                        cols="12"
                        md="2"
                    >
                        <VSelect
                            v-model="servicio.iva_percent"
                            :items="array_iva"
                            item-value="descripcion"
                            item-title="descripcion"
                            filled
                            label="IVA"
                            class="mx-3"
                        >
                        </VSelect>
                    </VCol>

                    <VCol cols="12" md="2">
                        <VTextField
                            hide-details
                            filled
                            readonly
                            v-model="servicio.importe"
                            label="Importe"
                        ></VTextField>
                    </VCol>
                </VRow>
                <!--/ datos del servicio -->

                <!-- boton agregar articulo o añadir albaranes  -->
                <VRow dense class="mt-3">
                    <VCol class="mt-1" cols="6" md="5" sm="12">
                        <VBtn
                            rounded="pill"
                            @click="addService"
                            :disabled="isloading"
                            class="mb-3 me-2"
                            >agregar
                        </VBtn>
                        <VBtn
                            v-if="
                                validadorUrl ==
                                    '/guardar-recibo?tipo=factura' ||
                                validadorUrl ==
                                    `/guardar-recibo?id=${recibo.id}&tipo=factura` ||
                                validadorUrl ==
                                    `/guardar-recibo?tipo=facturaproforma` ||
                                validadorUrl ==
                                    `/guardar-recibo?id=${recibo.id}&tipo=facturaroforma`
                            "
                            rounded="pill"
                            color="#5142A6"
                            class="mb-3 text-white"
                            @click="modalAddAlbaranesFuncion()"
                            :disabled="isloading"
                        >
                            Añadir albaranes
                        </VBtn>
                        <!-- Espacio para errores -->
                    </VCol>
                    <VCol
                        v-if="errors.errors.servicios"
                        cols="12"
                        sm="12"
                        md="3"
                        lg="3"
                        xl="3"
                    >
                        <VAlert outlined color="red"
                            ><strong>{{
                                errors.errors.servicios[0]
                            }}</strong></VAlert
                        >
                    </VCol>
                </VRow>
                <!--/ boton agregar servicio o añadir albaranes  -->

                <!-- tabla servicios -->
                <VRow dense class="mt-5">
                    <VCol cols="12" md="12">
                        <VDataTable
                            :headers="headers"
                            :items="recibo.servicios"
                            disable-pagination
                            hide-default-footer
                            items-per-page="-1"
                            item-key="id"
                            class="elevation-1"
                        >
                            <template v-if="batchEnabled" v-slot:item.lote="{ item }">
                                {{ item.lote || "-" }}
                            </template>
                            <template v-slot:item.precio="{ item }">
                                {{ formatPrice(parseEuroNumber(item.precio)) }}€
                            </template>
                            <template v-slot:item.importe="{ item }">
                                {{
                                    formatPrice(parseEuroNumber(item.importe))
                                }}€
                            </template>
                            <template v-slot:item.action="{ item }">
                                <VIcon
                                    v-show="!isAlbaran(item)"
                                    @click="setItem(item)"
                                    small
                                    class="mr-2"
                                    color="blue"
                                    >ri-pencil-line</VIcon
                                >
                                <VIcon
                                    :class="{ 'ml-7': isAlbaran(item) }"
                                    @click="deleteItem(item, checkbox)"
                                    small
                                    color="red"
                                    >ri-delete-bin-line</VIcon
                                >
                            </template>
                        </VDataTable>
                    </VCol>
                </VRow>
                <!--/ tabla servicios -->

                <!-- observaciones -->
                <VRow
                    dense
                    v-if="
                        tipo == 'factura' ||
                        tipo == 'facturarectificativa' ||
                        tipo == 'facturaproforma' ||
                        tipo == 'nota' ||
                        tipo == 'presupuesto'
                    "
                    class="mt-3"
                >
                    <VCol cols="12" md="12">
                        <p
                            v-if="
                                tipo == 'factura' || tipo == 'facturaproforma'
                            "
                            class="mb-2"
                        >
                            <strong>OBSERVACIONES</strong>
                        </p>
                        <p v-else><strong>OBSERVACIONES</strong></p>
                        <VTextarea
                            solo
                            v-model="recibo.observaciones"
                            label="Observaciones"
                        ></VTextarea>
                    </VCol>
                </VRow>

                <VRow dense class="mt-5">
                    <VCol cols="12" md="12">
                        <!-- titulo descuento -->
                        <VRow dense>
                            <VCol cols="12" md="12">
                                <p
                                    v-if="
                                        tipo == 'facturarectificativa' ||
                                        tipo == 'factura'
                                    "
                                >
                                    <strong>DESCUENTO - IVA - TOTALES</strong>
                                </p>
                                <p v-else>
                                    <strong>DESCUENTO - TOTALES</strong>
                                </p>
                            </VCol>
                        </VRow>

                        <VRow dense class="mt-3" align="center">
                            <!-- descuento -->
                            <VCol
                                cols="12"
                                md="2"
                                sm="2"
                                lg="2"
                                xl="2"
                                class="d-flex flex-column align-center"
                            >
                                <strong>
                                    <p
                                        style="
                                            font-size: 11px;
                                            text-align: center;
                                        "
                                    >
                                        TIPO DESCUENTO
                                    </p>
                                </strong>

                                <VBtnToggle
                                    v-model="recibo.tipo_descuento"
                                    rounded="pill"
                                    density="compact"
                                    class="mb-2"
                                >
                                    <VBtn
                                        v-if="recibo.tipo_descuento == 0"
                                        label="Descuento %"
                                        color="primary"
                                        small
                                        elevation="3"
                                    >
                                        <VIcon>ri-percent-line</VIcon>
                                    </VBtn>
                                    <VBtn v-else small elevation="3">
                                        <VIcon>ri-percent-line</VIcon>
                                    </VBtn>

                                    <VBtn
                                        v-if="recibo.tipo_descuento == 1"
                                        label="Descuento EUROS"
                                        color="primary"
                                        small
                                        elevation="3"
                                    >
                                        <VIcon>ri-money-euro-circle-line</VIcon>
                                    </VBtn>
                                    <VBtn v-else small elevation="3">
                                        <VIcon>ri-money-euro-circle-line</VIcon>
                                    </VBtn>
                                </VBtnToggle>
                            </VCol>

                            <VCol cols="12" md="2" sm="2" lg="2" xl="2">
                                <VTextField
                                    filled
                                    type="number"
                                    v-model="recibo.descuento"
                                    label="Descuento"
                                    hide-details
                                    @input="
                                        recibo.descuento = inputPrice(
                                            recibo.descuento
                                        )
                                    "
                                ></VTextField>
                            </VCol>
                            <!--/ descuento -->

                            <!-- IVA -->
                            <template
                                v-if="
                                    tipo == 'facturarectificativa' ||
                                    tipo == 'factura' ||
                                    tipo == 'presupuesto'
                                "
                            >
                                <VCol
                                    cols="12"
                                    md="4"
                                    sm="3"
                                    lg="3"
                                    xl="3"
                                    class="d-flex mx-3"
                                >
                                    <!--VSwitch
                                        :inset="false"
                                        v-if="recibo.has_iva == false"
                                        v-model="recibo.has_iva"
                                        label="IVA NO Incluido"
                                        style=""></VSwitch>
                                    <VSwitch
                                        :inset="false"
                                        v-else
                                        v-model="recibo.has_iva"
                                        label="IVA Incluido"
                                        style=""></VSwitch>

                                    VSelect
                                        v-if="recibo.has_iva == true"
                                        v-model="recibo.tipo_iva"
                                        :items="array_iva"
                                        item-value="descripcion"
                                        item-title="descripcion"
                                        filled
                                        label="I.V.A"
                                        value="21"
                                        class="mx-3"></VSelect-->
                                </VCol>
                            </template>

                            <!-- select de presupuestos -->
                            <VCol
                                cols="12"
                                md="6"
                                sm="6"
                                lg="6"
                                xl="6"
                                v-if="tipo == 'parte-trabajo'"
                            >
                                <VSelect
                                    label="Presupuesto"
                                    v-model="presupuesto"
                                    return-object
                                    :items="presupuestos"
                                    item-text="nro_presupuesto"
                                    item-value="id"
                                ></VSelect>
                                <p>
                                    <strong>Importe:</strong>
                                    {{ this.presupuesto.total }}€
                                    <strong class="pl-2">Beneficio:</strong>
                                    {{ beneficio }}
                                </p>
                            </VCol>

                            <VSpacer></VSpacer>

                            <!-- totales -->
                            <display-totales
                                v-if="
                                    tipo == 'presupuesto' ||
                                    tipo == 'factura' ||
                                    tipo == 'facturarectificativa' ||
                                    tipo == 'facturaproforma' ||
                                    tipo == 'parte-trabajo'
                                "
                                :value="totales"
                                :tipo="tipo"
                                :has_iva="recibo.has_iva"
                                @input="updateTotales"
                                :IVAs="IVAs"
                            >
                            </display-totales>
                            <display-total
                                v-if="tipo == 'nota'"
                                :total="recibo.total"
                                :recibo="totales"
                            ></display-total>
                        </VRow>
                    </VCol>
                </VRow>
            </VForm>
        </VCardText>

        <VDivider class="mt-5"></VDivider>

        <!-- botones-->
        <VCardText>
            <VRow dense>
                <VBtn
                    :to="path_volver"
                    rounded="pill"
                    variant="outlined"
                    color="secondary"
                    class="mr-1"
                >
                    volver
                </VBtn>

                <!-- factura -->
                <template v-if="tipo == 'factura'">
                    <botones-factura
                        :isloading="isloading"
                        :tipo="tipo"
                        :recibo="recibo"
                        v-on:save_factura="saveFactura"
                    />
                </template>

                <!-- factura rectificativa -->
                <botones-factura
                    class="mt-3"
                    v-if="tipo == 'facturarectificativa'"
                    :isloading="isloading"
                    :tipo="tipo"
                    :recibo="recibo"
                    v-on:save_factura="saveFacturaR"
                />

                <!-- factura proforma -->
                <template v-if="tipo == 'facturaproforma'">
                    <botones-factura
                        class="mt-3"
                        :isloading="isloading"
                        :tipo="tipo"
                        :recibo="recibo"
                        v-on:save_factura="saveFacturaProforma"
                    />
                </template>

                <!-- botones presupuesto -->
                <botones-presupuesto
                    class="mt-3"
                    v-if="tipo == 'presupuesto'"
                    :isloading="isloading"
                    :tipo="tipo"
                    :recibo="recibo"
                    v-on:save_presupuesto="savePresupuesto"
                    v-on:convertir_factura="convertirFactura"
                    v-on:convertir_nota="convertirNota"
                />

                <!-- botones parte trabajo -->
                <template v-if="tipo == 'parte-trabajo'">
                    <botones-parte-trabajo
                        :isloading="isloading"
                        :tipo="tipo"
                        :recibo="recibo"
                        v-on:save_parte_trabajo="saveParteTrabajo"
                        v-on:convertir_factura="convertirFactura"
                        v-on:convertir_nota="convertirNota"
                    >
                    </botones-parte-trabajo>
                </template>

                <template v-if="tipo == 'nota'">
                    <VBtn
                        :disabled="isloading"
                        rounded="pill"
                        class="mx-1"
                        @click="saveRecibo('nota')"
                        >guardar albarán
                    </VBtn>
                    <VBtn
                        :disabled="isloading"
                        rounded="pill"
                        color="#5142A6"
                        class="mx-1 text-white"
                        v-if="recibo.nota_url != null && tipo == 'nota'"
                        @click="saveRecibo('factura', true)"
                    >
                        convertir a factura
                    </VBtn>
                    <VBtn
                        :disabled="isloading"
                        rounded="pill"
                        color="#5142A6"
                        class="mx-1 text-white"
                        v-if="recibo.nota_url != null && tipo == 'nota'"
                        target="_blank"
                        :href="`/storage/recibos/userId_${recibo.user_id || user_id}/${recibo.nota_url}`"
                        >ver pdf
                    </VBtn>
                </template>

                <VBtn
                    v-if="recibo.id != null"
                    :disabled="isloading"
                    rounded="pill"
                    color="#5142A6"
                    @click="email_dialog = true"
                    class="mx-1 text-white"
                    >enviar email</VBtn
                >
            </VRow>
        </VCardText>
    </VCard>

    <email-content-dialog
        :isloading="isloading"
        v-on:close_dialog="closeDialog"
        :email_dialog="email_dialog"
        :url_files="url_files"
        :tipo="tipo"
        :email="recibo.cliente.email"
        :id_factura="recibo.id"
        :user_id="user_id"
    >
    </email-content-dialog>

    <AddAlbaranes
        :modalAddAlbaranes="modalAddAlbaranes"
        :closeModalAlbaranes="closeModalAlbaranes"
        :albaranesEnviados="albaranesEnviados"
        :closeModalAlbaranesListo="closeModalAlbaranesListo"
        :addAlbaranAlaLista="addAlbaranAlaLista"
        :deleteItem="deleteItem"
        :checkbox="checkbox"
        :obtenerServicio="obtenerServicio"
    />

    <Confirmar
        :modalConfirm="modalConfirm"
        :convertirFacturaConfirmado="convertirFacturaConfirmado"
        :itemPdf="itemPdf"
        :closeModal="closeModal"
        @convertirNotaConfirmado="convertirNotaConfirmado"
        :ConvertirPresupuestoAFactura="ConvertirPresupuestoAFactura"
    />
</template>

<script>
import { ParamSystemEnum } from "@/@core/types/ParamSystem.enum";
import { requiredValidator } from "@/@core/utils/validators";
import gestorClienteMixin from "@/global_mixins/gestorClienteMixin.js";
import { $api } from "@/utils/api";
import ClienteSelect from "../../components/ClienteSelect.vue";
import CRUDSelect from "../../components/CRUDSelect.vue";
import CustomerForm from "../../components/CustomerForm.vue";
import AddAlbaranes from "./AddAlbaranes.vue";
import botonesFactura from "./botonesFactura.vue";
import botonesParteTrabajo from "./botonesParteTrabajo.vue";
import botonesPresupuesto from "./botonesPresupuesto.vue";
import Confirmar from "./Confirmar.vue";
import displayTotal from "./displayTotal.vue";
import displayTotales from "./displayTotales.vue";
import emailContentDialog from "./emailContentDialog.vue";
import nro_recibo_mixin from "./mixins/nro_recibo_mixin";
import { servicios_mixin } from "./mixins/servicios_mixin";
import { total_mixin } from "./mixins/total_mixin";
import { useSeries } from "./mixins/useSeries";

const {
    findAllSeries,
    series,
    form_series,
    submitSeries,
    onEditSerie,
    onResetSerieForm,
    onDeleteSerie,
} = useSeries();

export default {
    components: {
        CRUDSelect,
        displayTotales,
        displayTotal,
        emailContentDialog,
        botonesFactura,
        botonesPresupuesto,
        botonesParteTrabajo,
        AddAlbaranes,
        Confirmar,
        requiredValidator,
        CustomerForm,
        ClienteSelect,
    },

    mixins: [
        gestorClienteMixin,
        total_mixin,
        servicios_mixin,
        nro_recibo_mixin,
    ],

    data() {
        return {
            // series: ref([]),
            // form_series: {},

            validForm: false,
            batchEnabled: false,

            // Generales
            user_id: localStorage.getItem("user_id"),
            titulo: {
                facturaproforma: "FACTURA PROFORMA",
                factura: "FACTURA",
                nota: "NOTA",
                presupuesto: "PRESUPUESTO",
            },
            tipo: "factura",

            // Auxiliares
            menu: false,
            menu2: null,
            email_dialog: false,
            modalAddAlbaranes: false,
            modalConfirm: false,
            validadorUrl: "",
            iva: true,
            calcular_iva: true,
            itemPdf: "",

            // Recibo
            recibo: {
                id: null,
                serie_id: null,
                cliente_id: null,
                cliente: {
                    id: null,
                    email: "",
                },
                fecha: new Date().toISOString().substr(0, 10),
                fecha_tope: new Date().toISOString().substr(0, 10),
                sub_total: 0,
                tipo_descuento: 0,
                descuento: 0,
                total_descuento: 0,
                total: 0,
                observaciones: "",
                presupuesto_url: null,
                factura_url: null,
                nota_url: null,
                has_iva: true,
                servicios: [],
                iva: 0,
                tipo_iva: 21,
                user_id: localStorage.getItem("user_id"),
                albaranes: [],
                fecha_recurrente: new Date().getDate(),
            },
            servicios: [],
            servicio: {
                recibo_id: null,
                id: `temp-${new Date().getTime()}`,
                descripcion: "",
                id_servicio: null,
                cantidad: 1,
                precio: 0,
                importe: "",
                iva_percent: 0,
                lote: "",
                user_id: localStorage.getItem("user_id"),
            },

            // Clientes
            clientes: [],
            selectedCustomer: this.initCustomerForm(),
            // Presupuesto
            presupuestos: [],
            presupuesto: {
                id: null,
                total: 0,
                nro_presupuesto: null,
                user_id: localStorage.getItem("user_id"),
            },

            // Arrays generales
            albaranesEnviados: [],
            checkbox: [],
            albaranesSeleccionados: [],
            array_iva: [],

            // Metodos de pago
            user_metodo_pago: {},
            metodo_predeterminado: {
                nombre: "",
                field: "",
                detalle: "",
            },
            metodos_pago: [
                {
                    nombre: "Transferencia Bancaria",
                    field: "pago_uno",
                    detalle: null,
                },
                {
                    nombre: "Giro Bancario",
                    field: "pago_dos",
                    detalle: null,
                },
                {
                    nombre: "Efectivo",
                    field: "pago_tres",
                    detalle: null,
                },
                {
                    nombre: "Paypal",
                    field: "pago_cuatro",
                    detalle: null,
                },
                {
                    nombre: "Bizum",
                    field: "pago_cinco",
                    detalle: null,
                },
            ],

            test: {
                a: 1,
                b: 2,
            },
        };
    },

    mounted() {
        this.validadorUrl = this.$route.href;

        this.dataGet(); // Obtenemos albaranes de cliente sin contabilidad en --> this.consulta

        this.tipo == "parte-trabajo" ? this.getPrespuestos() : null;
        this.findAllSeries();
    },

    async created() {
        this.tipo = this.$route.query.tipo || "presupuesto";

        await this.loadBatchSetting();
        if (this.$route.query.id) {
            await this.getReciboById(this.$route.query.id);

            if (this.tipo == "parte-trabajo") {
                this.getPresupuestoAsociado(this.$route.query.id);
            }
        }

        if (this.tipo == "nota") {
            this.recibo.has_iva = false;
        }

        this.getServicios();
        // this.getClientes(); // Comentado porque ClienteSelect usa el store
        this.getArrayIva();
        // this.getMetodosPago();
    },
    watch: {
        "servicio.id_servicio"(val) {
            // console.log("id_servicio", val);
            const servicio = this.servicios.find((ele) => ele.id == val);
            if (servicio) {
                this.servicio.descripcion = servicio.descripcion;
                this.servicio.precio = parseFloat(servicio.precio);
                const ivaValue =
                    servicio.iva_percent !== undefined &&
                    servicio.iva_percent !== null &&
                    servicio.iva_percent !== ""
                        ? servicio.iva_percent
                        : 0;
                this.servicio.iva_percent = ivaValue;
                if (
                    servicio.lote !== undefined &&
                    servicio.lote !== null &&
                    servicio.lote !== ""
                ) {
                    this.servicio.lote = servicio.lote;
                }
            }
        },
        "metodo_predeterminado.field": {
            handler(n) {
                if (n) {
                    this.metodo_predeterminado.detalle =
                        this.user_metodo_pago[n];
                }
            },
            deep: true,
        },
        servicio: {
            handler(n) {
                // console.log(n);
            },
            deep: true,
        },
        "recibo.metodo_pago"(n) {
            if (n) {
                this.user_metodo_pago[n] = this.recibo.detalle_pago;
                let metodo = this.metodos_pago.find((x) => x.field == n);
                metodo.detalle = this.recibo.detalle_pago;
                this.metodo_predeterminado = metodo;
            }
        },
        "recibo.cliente_id"(cliente_id) {
            this.getDataAlbaranes(cliente_id);
            this.onChangeCustomer(cliente_id);
        },
        "servicio.cantidad"(n) {
            this.servicio.importe = formatPrice(
                n * parseEuroNumber(this.servicio.precio)
            );
        },

        "servicio.precio"(n) {
            this.servicio.importe = formatPrice(
                parseEuroNumber(n) * this.servicio.cantidad
            );
        },

        "recibo.servicios"(n) {
            if (n.length > 0) {
                this.calcularTotales(n);
            }
        },

        "recibo.descuento"(n) {
            if (this.recibo.servicios.length > 0) {
                this.calcularTotales(this.recibo.servicios);
            }
        },

        "recibo.has_iva"(n) {
            if (this.recibo.servicios.length > 0) {
                this.calcularTotales(this.recibo.servicios);
            }
        },

        "recibo.tipo_iva"(n) {
            if (this.recibo.servicios.length > 0) {
                this.calcularTotales(this.recibo.servicios);
            }
        },

        "recibo.tipo_descuento"(n) {
            if (this.recibo.servicios.length > 0) {
                this.calcularTotales(this.recibo.servicios);
            }
        },

        "recibo.eur_descuento"(n) {
            if (this.recibo.servicios.length > 0) {
                this.calcularTotales(this.recibo.servicios);
            }
        },

        "recibo.recurrente"(newValue) {
            if (newValue) {
                this.recibo.fecha_recurrente =
                    this.recibo.fecha_recurrente != null
                        ? this.recibo.fecha_recurrente
                        : new Date(this.recibo.fecha).getDate();
            } else {
                this.recibo.fecha_recurrente = null;
            }
        },
    },
    methods: {
        // Recibo
        async getReciboById(recibo_id) {
            try {
                const res = await axios.get(
                    `api/get-recibo-by-id/${recibo_id}`
                );
                this.recibo = res.data;
                if (Array.isArray(this.recibo.servicios)) {
                    this.recibo.servicios = this.recibo.servicios.map(
                        (servicio) => ({
                            ...servicio,
                            iva_percent:
                                servicio.iva_percent === undefined ||
                                servicio.iva_percent === null
                                    ? 0
                                    : servicio.iva_percent,
                            cantidad:
                                servicio.cantidad === undefined ||
                                servicio.cantidad === null ||
                                servicio.cantidad === ""
                                    ? 1
                                    : servicio.cantidad,
                            precio:
                                servicio.precio === undefined ||
                                servicio.precio === null ||
                                servicio.precio === ""
                                    ? 0
                                    : servicio.precio,
                            lote:
                                servicio.lote === undefined ||
                                servicio.lote === null
                                    ? ""
                                    : servicio.lote,
                        })
                    );
                }
                if (
                    this.servicio.iva_percent === undefined ||
                    this.servicio.iva_percent === null
                ) {
                    this.servicio.iva_percent = 0;
                }
                if (
                    this.servicio.lote === undefined ||
                    this.servicio.lote === null
                ) {
                    this.servicio.lote = "";
                }
                if (
                    this.servicio.cantidad === undefined ||
                    this.servicio.cantidad === "" ||
                    this.servicio.cantidad === null
                ) {
                    this.servicio.cantidad = 1;
                }
                if (
                    this.servicio.precio === undefined ||
                    this.servicio.precio === "" ||
                    this.servicio.precio === null
                ) {
                    this.servicio.precio = 0;
                }
                this.loadDocumentNumber();
            } catch (error) {
                $toast.error("Error consultando Factura");
            }
        },
        getPresupuestoAsociado(recibo_id) {
            axios.get(`api/get-presupuesto-asociado/${recibo_id}`).then(
                (res) => {
                    if (JSON.stringify(res.data) !== "{}") {
                        this.presupuesto = res.data;
                    }
                },
                (res) => {
                    $toast.error("Error cargando presupuesto");
                }
            );
        },

        saveRecibo(tipo, convertir_factura = false) {
            if (tipo == "parte-trabajo") {
                this.recibo.nro_presupuesto_id =
                    this.presupuesto.nro_presupuesto_id;
            }

            this.recibo.albaranes = this.albaranesSeleccionados;
            let reciboalbaranes = this.recibo;
            reciboalbaranes.checkbox = this.checkbox;
            reciboalbaranes.observaciones = this.recibo.observaciones;

            if (this.recibo.has_iva == false) {
                this.recibo.tipo_iva = 0;
                this.recibo.iva = 0;
            }

            this.recibo.document_number = this.document_number;
            this.recibo.can_modify_document_number =
                [
                    "presupuesto",
                    "factura",
                    "facturarectificativa",
                    "facturaproforma",
                    "parte-trabajo",
                    "nota",
                ].includes(this.tipo) || false;
            this.recibo.type = this.tipo;

            reciboalbaranes.metodo = this.metodo_predeterminado;
            this.$refs.form.validate();

            if (this.validForm) {
                axios
                    .post(
                        `api/save-recibo/${tipo}/${convertir_factura}`,
                        reciboalbaranes
                    )
                    .then((res) => {
                        if (res.status === 200) {
                            this.recibo = res.data.recibo;
                            if (!this.recibo.fecha_recurrente) {
                                this.recibo.fecha_recurrente =
                                    new Date().getDate();
                            }

                            this.loadDocumentNumber();
                            $toast.sucs(res.data.message);

                            if (res.data.metodo == "pago_dos") {
                                $toast.info(
                                    "Debe descargar la orden sepa, para enviar a su cliente, para ese medio de pago guardado en la factura"
                                );
                            }
                            this.changeParams(tipo);
                        }
                    })
                    .catch((err) => {
                        console.log("err", err);
                        const errorMessage =
                            err.response?.data?.error ||
                            err.response?.data?.message ||
                            "Error desconocido";
                        $toast.error(errorMessage);
                    });
            }
        },

        // auxiliares recibo
        saveFactura() {
            this.saveRecibo("factura", false);
        },
        saveFacturaR() {
            this.saveRecibo("facturarectificativa", false);
        },
        saveFacturaProforma() {
            this.saveRecibo("facturaproforma", false);
        },
        savePresupuesto() {
            this.saveRecibo("presupuesto");
        },
        saveParteTrabajo() {
            this.saveRecibo("parte-trabajo");
        },
        changeParams(tipo_recibo) {
            let current_path = this.$route.path;
            let id = this.$route.query.id;
            this.tipo = tipo_recibo;
            this.$router
                .push({
                    path: current_path,
                    query: {
                        id: id,
                        tipo: tipo_recibo,
                    },
                })
                .catch(() => {});
        },

        // Acciones de los botones
        convertirFactura() {
            this.modalConfirm = true;
            this.itemPdf = ", Factura";
        },
        ConvertirPresupuestoAFactura() {
            this.saveRecibo("factura", true);
            this.modalConfirm = false;
            this.itemPdf = "";
        },

        convertirNota() {
            this.modalConfirm = true;
            this.itemPdf = "Nota";
        },

        convertirNotaConfirmado() {
            this.recibo.iva = 0;
            let total = this.recibo.sub_total - this.recibo.total_descuento;
            this.recibo.total = parseFloat(total).toFixed(2);
            this.saveRecibo("nota", true);
            this.modalConfirm = false;
            this.itemPdf = "";
        },

        convertirFacturaConfirmado() {
            this.recibo.has_iva = true;
            this.tipo = "factura";
            this.recibo.has_iva == true
                ? this.calcularTotales(this.recibo.servicios)
                : null;
            this.saveRecibo("factura", true);
            this.modalConfirm = false;
            this.itemPdf = "";
        },

        // Generales
        getServicios() {
            axios.get(`api/get-servicios?venta=1`).then(
                (res) => {
                    this.servicios = (res.data || []).map((servicio) => ({
                        ...servicio,
                        lote:
                            servicio.lote === undefined ||
                            servicio.lote === null
                                ? ""
                                : servicio.lote,
                    }));
                },
                (err) => {
                    $toast.error("Error consultando servicios");
                }
            );
        },
        onClienteChanged(event) {
            console.log(
                "FormRecibo: Cliente cambiado, recargando servicios...",
                event.detail
            );
            this.getServicios();
        },
        getClientes() {
            axios.get(`api/get-clientes`).then(
                (res) => {
                    this.clientes = res.data.data || res.data;
                },
                (res) => {
                    $toast.error("Error consultando Clientes");
                }
            );
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
        getMetodosPago() {
            axios.get(`api/get-metodos-pago`).then(
                (res) => {
                    if (!res.data.id) {
                        return $toast.warn("No hay forma de pago configurada");
                    }
                    this.user_metodo_pago = res.data;
                    let metodo = this.metodos_pago.find(
                        (x) => x.field == res.data.predeterminado
                    );
                    this.metodo_predeterminado = metodo;
                },
                (res) => {
                    $toast.error("Error consultando formas de pago");
                }
            );
        },
        getPrespuestos() {
            axios
                .get(
                    `api/get-presupuestos-for-parte-trabajo/${localStorage.getItem(
                        "user_id"
                    )}`
                )
                .then(
                    (res) => {
                        this.presupuestos = res.data;
                    },
                    (res) => {
                        $toast.error("Error cargando presupuestos");
                    }
                );
        },
        getDataAlbaranes(cliente_id) {
            if (!cliente_id) return;
            axios.get(`api/get-data-albaranes/${cliente_id}`).then(
                (res) => {
                    this.albaranesEnviados = res.data.albaranesEnviados;
                    this.consulta = res.data.albaranesEnviados;
                },
                (res) => {
                    $toast.error("Error consultando albaranes Enviados");
                }
            );
        },
        dataGet() {
            axios
                .get(
                    `api/get-data-albaranes/${localStorage.getItem("user_id")}`
                )
                .then(
                    (res) => {
                        this.albaranesEnviados = res.data.albaranesEnviados;
                        this.consulta = res.data.albaranesEnviados;
                        // console.log('***** albaranes consulta *****' + JSON.stringify(this.consulta));
                    },
                    (res) => {
                        $toast.error("Error consultando albaranes Enviados");
                    }
                );
        },

        async loadBatchSetting() {
            try {
                const headers = {};
                const selectedCliente =
                    localStorage.getItem("selected_cliente_id");
                if (selectedCliente) {
                    headers["X-Selected-Cliente-Id"] = selectedCliente;
                }

                const res = await $api(
                    `/api/system-params/by-name/${ParamSystemEnum.ENABLE_BATCH}`,
                    {
                        method: "GET",
                        headers,
                    }
                );

                const value = res?.value;
                this.batchEnabled =
                    value === true ||
                    value === 1 ||
                    value === "1" ||
                    value === "true";
            } catch (error) {
                console.warn(
                    "No se pudo obtener la configuración de lote",
                    error
                );
                this.batchEnabled = false;
            }
        },

        // auxiliares
        closeModal() {
            this.modalConfirm = false;
        },
        volver() {
            this.$router.push(`/lista-facturas`);
        },
        volverProforma() {
            this.$router.push(`/lista-facturas-proforma`);
        },
        closeDialog() {
            this.email_dialog = false;
        },

        // Modal de albaran
        addAlbaranAlaLista(item) {
            this.item = item;
            this.item.descripcion = "Albaran_" + item.nro_factura;
            this.item.cantidad = 1;
            this.item.precio = item.importe;
            this.item.id = `temp-${new Date().getTime()}`;
            this.item.lote = this.item.lote ?? "";
            this.servicio = this.item;
            this.addService();
            this.calcularTotales(this.recibo.servicios);
            if (this.tipo == "factura") {
                this.saveFactura();
            } else if (this.tipo == "facturaproforma") {
                this.saveFacturaProforma();
            } else {
            }
        },
        modalAddAlbaranesFuncion() {
            this.modalAddAlbaranes = true;
        },
        closeModalAlbaranes(checkbox) {
            let confirmar = confirm(
                "Al cerrar el cuadro los elementos seleccionados no se agregaran a tu Factura.¿Estás de acuerdo?"
            );
            if (confirmar != false) {
                for (let index = 0; index < checkbox.length; index++) {
                    if (checkbox[index] != null) {
                        for (let z = 0; z < this.recibo.servicios.length; z++) {
                            let albaranEliminar = "Albaran_" + checkbox[index];
                            if (
                                this.recibo.servicios[z].descripcion ==
                                albaranEliminar
                            ) {
                                this.recibo.servicios.splice(z, 1);
                            }
                        }
                        checkbox[index] = undefined;
                    }
                    if (this.servicio.descripcion.length == 0) {
                        this.recibo.sub_total = 0;
                        this.recibo.iva = 0;
                        this.recibo.total_descuento = 0;
                        this.recibo.total = 0;
                    }
                    this.modalAddAlbaranes = false;
                    this.checkbox = [];
                    this.albaranesSeleccionados = [];
                }
            }
        },
        closeModalAlbaranesListo() {
            this.modalAddAlbaranes = false;
        },

        /**
         * @param {number} customer_id
         */
        async onChangeCustomer(customer_id) {
            if (customer_id) {
                this.selectedCustomer = await this.findCustomerById(
                    customer_id
                );
            } else {
                // Solo resetear si realmente no hay cliente seleccionado
                this.selectedCustomer = this.initCustomerForm();
            }
        },
        /**
         * @param {number} id
         * @returns {Promise<object>}
         * @description returns the customer by id or null if not found it
         */
        async findCustomerById(id) {
            if (!id || typeof id != "number") return this.initCustomerForm();
            return (await axios.get(`/api/get-cliente-by-id/${id}`)).data;
        },
        initCustomerForm() {
            return {
                id: null,
                nro_cliente: "",
                fecha_alta: new Date(),
                activo: "",
                nombre_comercial: "",
                nombre: "",
                dni: "",
                email: "",
                telefono: "",
                direccion: "",
                codigo_postal: "",
                provincia_id: "",
                localidad: "",
                observaciones: "",
                contacto_nombre: "",
                contacto_telefono: "",
                banco: "",
                forma_pago_id: "",
                historial: "",
            };
        },

        updateTotales(newTotales) {
            this.recibo.sub_total = newTotales.sub_total;
            this.recibo.iva = newTotales.iva;
            this.recibo.porcentaje_descuento = newTotales.porcentaje_descuento;
            this.recibo.total_descuento = newTotales.total_descuento;
            this.recibo.total = newTotales.total;
        },

        // Metodos de series de factura
        findAllSeries,
        submitSeries,
        onEditSerie,
        onResetSerieForm,
        onDeleteSerie,

        // En desuso
        /*async onCustomerSearch(e) {
            const vm = this;
            const customerSearch = CustomerSearch.getInstance();
            customerSearch.debounce(async function () {
                const clientes = await customerSearch.search(e.target.value);
                vm.clientes = clientes;
                console.log(clientes, " busqueda del API");
            }, 800)();
        },
         openModalConfirmar() {
            this.modalConfirm = true;
        },
        convertirNotaFactura() {
            this.modalConfirm = true;
            this.itemPdf = "Factura";
        },*/
    },
    computed: {
        isloading() {
            return this.$store.getters.getloading;
        },
        errors() {
            return this.$store.getters.geterrors;
        },
        totales: {
            get() {
                return {
                    sub_total: this.recibo.sub_total ?? 0,
                    iva: this.recibo.iva ?? 0,
                    porcentaje_descuento: this.recibo.porcentaje_descuento ?? 0,
                    total_descuento: this.recibo.total_descuento ?? 0,
                    total: this.recibo.total ?? 0,
                };
            },
            set(newValue) {
                // Actualizar los valores del recibo cuando cambien los totales
                this.recibo.sub_total = newValue.sub_total;
                this.recibo.iva = newValue.iva;
                this.recibo.porcentaje_descuento =
                    newValue.porcentaje_descuento;
                this.recibo.total_descuento = newValue.total_descuento;
                this.recibo.total = newValue.total;
            },
        },
        url_files() {
            return {
                presupuesto_url:
                    "userId_" +
                    this.user_id +
                    "/" +
                    this.recibo.presupuesto_url,
                factura_url:
                    "userId_" + this.user_id + "/" + this.recibo.factura_url,
                nota_url: "userId_" + this.user_id + "/" + this.recibo.nota_url,
            };
        },
        userId() {
            return localStorage.getItem("user_id");
        },
        beneficio() {
            let total_recibo = this.recibo.has_iva
                ? this.recibo.total
                : this.recibo.sub_total;
            let beneficio_not_parsed =
                parseFloat(this.presupuesto.total) - parseFloat(total_recibo);
            let beneficio_parsed = parseFloat(beneficio_not_parsed).toFixed(2);
            return `${beneficio_parsed}€`;
        },

        title() {
            let title = "";

            if (this.tipo == "facturaproforma")
                title =
                    this.$route.query.id != null
                        ? "EDITAR FACTURA PROFORMA"
                        : "CREAR FACTURA PROFORMA";
            else if (this.tipo == "factura")
                title =
                    this.$route.query.id != null
                        ? "EDITAR FACTURA"
                        : "CREAR FACTURA";
            else if (this.tipo == "nota") {
                title =
                    this.$route.query.id != null
                        ? "EDITAR ALBARÁN"
                        : "CREAR ALBARÁN";
            } else if (this.tipo == "presupuesto")
                title =
                    this.$route.query.id != null
                        ? "EDITAR PRESUPUESTO"
                        : "CREAR PRESUPUESTO";
            else if (this.tipo == "facturarectificativa")
                title =
                    this.$route.query.id != null
                        ? "EDITAR FACTURA RECTIFICATIVA"
                        : "CREAR FACTURA RECTIFICATIVA";

            return title;
        },

        path_volver() {
            let path_volver = "";

            if (this.tipo == "facturaproforma")
                path_volver = `lista-facturas-proforma`;
            else if (this.tipo == "factura") path_volver = `lista-facturas`;
            else if (this.tipo == "nota") {
                path_volver = `lista-notas`;
            } else if (this.tipo == "presupuesto")
                path_volver = `lista-recibos`; // Sin user_id en la URL
            else if (this.tipo == "facturarectificativa")
                path_volver = `lista-facturas-rectificativas`;

            return path_volver;
        },

        series() {
            return series.value;
        },
        form_series: {
            get() {
                return form_series.value;
            },
            set(val) {
                form_series.value = val;
            },
        },
        IVAs() {
            const res = [];
            const vm = this;
            Object.keys(vm.recibo.IVAs ?? {}).forEach((key) => {
                res.push({
                    percent: `${key}%`,
                    percent_raw: key,
                    value: vm.recibo.IVAs[key],
                });
            });

            return res;
        },
        accordionTitle() {
            return this.selectedCustomer?.nombre
                ? this.selectedCustomer.nombre
                : "Cliente";
        },
    },
};
</script>

<style>
.v-snack__wrapper {
    min-width: 100% !important;
    max-width: 100% !important;
    border-radius: 30px !important;
}
.v-expansion-panel-text__wrapper {
    max-width: unset !important;
    padding: unset !important;
}
.expansion-panel-text {
    position: absolute;
    width: 35vw;
    height: 35rem;
    align-items: center;
    overflow-y: scroll;
}

@media screen and (max-width: 768px) {
    .expansion-panel-text {
        width: 100%;
    }
}
</style>
