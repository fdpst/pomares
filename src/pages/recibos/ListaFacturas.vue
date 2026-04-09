<template>
    <VCard
        class="pb-10"
        :title="
            $route.meta.tipo === 'facturarectificativa'
                ? 'Lista de facturas rectificativas'
                : 'Lista de facturas'
        ">
        <div class="ps-5 pe-5 pb-5">
            <loader v-if="isloading"></loader>

            <!-- Botones de acciones cuando hay facturas seleccionadas -->
            <VRow
                v-if="selected.length > 0"
                class="mb-3"
            >
                <VCol cols="12">
                    <div
                        class="d-flex flex-wrap align-center"
                        style="gap: 0.75rem;"
                    >
                        <VBtn
                            @click="printFacturas(false)"
                            rounded
                            depressed
                            color="primary"
                            class="text-on-primary"
                        >
                            Imprimir
                        </VBtn>
                        <VBtn
                            @click="printFacturas(true)"
                            rounded
                            depressed
                            color="primary"
                            class="text-on-primary"
                        >
                            Imprimir Resumen
                        </VBtn>
                        <VBtn
                            @click="
                                modalConfirm = true;
                                lotetext = defaultLoteText;
                            "
                            rounded
                            depressed
                            color="primary"
                            class="text-on-primary"
                        >
                            Enviar Mail
                        </VBtn>
                    </div>
                </VCol>
            </VRow>

            <VRow class="align-center">
                <VCol cols="12" md="8">
                    <div class="d-flex flex-column flex-md-row align-md-center" style="gap: 1rem;">
                        <VTextField
                            prepend-icon="ri-user-search-fill"
                            v-model="filter.search"
                            label="Búsqueda"
                            class="flex-grow-1"
                        ></VTextField>
                        <VCheckbox
                            label="Pendientes"
                            v-model="filter.pendientes"
                            hide-details
                            class="ma-0 pa-0 align-self-md-center"
                        ></VCheckbox>
                    </div>
                </VCol>

                <VCol cols="12" md="4" class="text-end">
                    <VBtn
                        rounded
                        depressed
                        color="primary"
                        class="mt-1"
                        :to="`/guardar-recibo?tipo=${$route.meta.tipo}`"
                    >
                        Nuevo
                    </VBtn>
                </VCol>
            </VRow>

            <VRow class="mt-3">
                <VCol cols="12" md="3">
                    <AppDateTimePicker
                        v-model="filter.fecha_desde"
                        label="Fecha desde"
                        prepend-icon="ri-calendar-fill"
                    />
                </VCol>
                <VCol cols="12" md="3">
                    <AppDateTimePicker
                        v-model="filter.fecha_hasta"
                        label="Fecha hasta"
                        prepend-icon="ri-calendar-fill"
                    />
                </VCol>
                <VCol cols="12" md="4">
                    <VAutocomplete
                        label="Servicio"
                        v-model="filter.servicio"
                        :items="servicios"
                        item-title="descripcion"
                        item-value="id"
                    ></VAutocomplete>
                </VCol>
            </VRow>
        </div>

        <VDataTable
            v-model="selected"
            :headers="headers"
            :items="facturas"
            :search="filter.search"
            item-key="id"
            class="elevation-1 mt-5"
            :items-per-page="-1"
            :show-select="true"
            :return-object="true">
            <template v-slot:item.pagado="{item}">
                <VIcon v-if="item.pagado">ri-check-line</VIcon>
            </template>
            <template v-slot:item.enviado="{item}">
                <VIcon v-if="item.enviado">ri-check-line</VIcon>
            </template>
            <template v-slot:item.archivo="{item}">
                <VIcon
                    v-if="item.nombre_factura != null"
                    medium
                    color="#FF4C51"
                    class="mr-2 cursor-pointer"
                    :class="{ 'opacity-50': regenerandoPdfId === item.id }"
                    :disabled="regenerandoPdfId === item.id"
                    @click="verPdf(item)">
                    mdi mdi-file-pdf-box
                </VIcon>
            </template>

            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="`/guardar-recibo?id=${item.id}&tipo=${$route.meta.tipo}`"
                    class="action-buttons"
                    color="grey-600">
                    <VIcon
                        small
                        class="mr-2"
                        color="grey-600">
                        ri-pencil-line
                    </VIcon>
                </RouterLink>
                <VIcon
                    small
                    class="mr-2"
                    color="blue"
                    @click="openDialog(item)">
                    ri-mail-line
                </VIcon>
                <VIcon
                    @click="showConfirmDialogDeleteFactura(item)"
                    small
                    class="mr-2"
                    color="red">
                    ri-delete-bin-line
                </VIcon>
            </template>
        </VDataTable>

        <email-content-dialog
            :isloading="isloading"
            v-on:close_dialog="
                () => {
                    closeDialog();
                    getFacturas();
                }
            "
            :email_dialog="email_dialog"
            :url_files="url_files"
            tipo="factura"
            :email="selected_item.cliente.email"
            :id_factura="selected_item.id"
            :user_id="dialogUserId || effectiveUserId"
            color="primary">
        </email-content-dialog>

        <VDialog
            v-model="modalConfirm"
            width="900px">
            <VCard>
                <VCardTitle class="text-h5 bg-warning text-center pa-4">
                    Enviar Lote Mail
                </VCardTitle>

                <VCardText class="mt-2">
                    <VTextField
                        dense
                        outlined
                        v-model="destinatarios"
                        label="Destinatario(s)"></VTextField>

                    <VTextField
                        class="mt-5"
                        dense
                        outlined
                        v-model="destinatariosCC"
                        label="Con copia (cc)"></VTextField>

                    <VTextField
                        class="mt-5"
                        dense
                        outlined
                        v-model="destinatariosCCO"
                        label="Copia oculta (cco)"></VTextField>

                    <VTextField
                        class="mt-5"
                        dense
                        outlined
                        v-model="email.asunto"
                        label="Asunto"></VTextField>

                    <RichTextComponent v-model="lotetext"></RichTextComponent>
                </VCardText>

                <VCardActions class="pt-3">
                    <VSpacer></VSpacer>

                    <VBtn
                        color="error"
                        large
                        @click="modalConfirm = false"
                        >Cancelar</VBtn
                    >

                    <VBtn
                        color="success"
                        large
                        @click="SendMailLote"
                        >Confirmar</VBtn
                    >
                </VCardActions>
            </VCard>
        </VDialog>
        <ConfirmDialogVue
            :modelValue="confirmDialog.modelValue"
            :title="confirmDialog.title"
            :text="confirmDialog.text"
            :color="confirmDialog.color"
            @confirm="confirmDialog.confirmAction"
            @cancel="confirmDialog.cancelAction" />
    </VCard>
</template>

<script>
import ConfirmDialogVue from "@/components/ConfirmDialog.vue";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import RichTextComponent from "./RichTextComponent.vue";
import emailContentDialog from "./emailContentDialog.vue";

export default {
    mixins: [gestorClienteMixin],
    components: {
        emailContentDialog,
        RichTextComponent,
        ConfirmDialogVue,
    },
    watch: {
        filter: {
            deep: true,
            handler: function (val) {
                this.saveFiltros();
            },
        },
        "filter.fecha_desde": function (val) {
            this.getFacturas();
        },
        "filter.fecha_hasta": function (val) {
            this.getFacturas();
        },
        // "filter.search": function (val) {
        //     this.getFacturas();
        // },
        selected(val) {
            console.log(val);
        },
        '$route.meta.tipo': function (newVal, oldVal) {
            if (newVal != oldVal) {
                this.getFacturas();
            }
        },
    },
    data() {
        return {
            confirmDialog: {
                modelValue: false,
                title: "",
                text: "",
                color: "success",
                confirmAction: () => void 0,
                cancelAction: () => void 0,
            },
            test: "",
            lotetext: "",
            defaultLoteText: "",
            modalConfirm: false,
            filter: {
                fecha_desde: null,
                fecha_hasta: null,
                servicio: null,
                pendientes: false,
                search: null,
            },
            url_files: null,
            dialogUserId: null,
            email_dialog: false,
            fecha_desde: null,
            fecha_hasta: null,
            selected: [],
            search: "",
            selected_item: {
                cliente: {email: null},
            },
            servicios: [],
            pendientes: false,
            facturas_items: [],
            headers: [
                {
                    title: "Nro.Factura",
                    align: "left",
                    value: "nro_factura",
                },
                {
                    title: "Fecha",
                    value: "fecha",
                },
                {
                    title: "Fecha de pago",
                    value: "fecha_pago",
                    width: "150",
                },
                {
                    title: "Cliente",
                    value: "cliente_nombre",
                },
                {
                    title: "Descripcion",
                    value: "observaciones",
                },
                {
                    title: "total",
                    value: "total",
                },
                {
                    title: "Pagada",
                    value: "pagado",
                },
                {
                    title: "Enviada",
                    value: "enviado",
                },
                {
                    title: "PDF",
                    value: "archivo",
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ],

            email: {
                destinatario: [],
                cc: [],
                cco: [],
                asunto: "",
            },
            destinatarios: "",
            destinatariosCC: "",
            destinatariosCCO: "Easy_poolspain@hotmail.com",
            regenerandoPdfId: null,
        };
    },
    mounted() {
        this.getFacturas();
        this.getFiltros();
        this.getServicios();
        this.getMailBody();
    },
    methods: {
        getFiltros() {
            const filtros = this.$store.getters.getFilters["facturas"];
            console.log("filtros", filtros);
            if (filtros != null) {
                this.filter = filtros;
            }
        },
        saveFiltros() {
            this.$store.dispatch("setFilter", {
                name: "facturas",
                data: this.filter,
            });
        },
        getServicios() {
            axios
                .get(`api/get-servicios`)
                .then(
                    (res) => {
                        this.servicios = res.data;
                    },
                    (err) => {
                        $toast.error("Error consultando servicios");
                    }
                );
        },
        async verPdf(item) {
            if (this.regenerandoPdfId === item.id) return;
            this.regenerandoPdfId = item.id;
            try {
                const { data } = await axios.get(
                    `api/recibos/${item.id}/regenerar-factura-pdf`
                );
                if (data.url) {
                    window.open(data.url, "_blank");
                    if (data.factura_url) {
                        const idx = this.facturas_items.findIndex((f) => f.id === item.id);
                        if (idx !== -1) {
                            this.facturas_items[idx].factura_url = data.factura_url;
                            this.facturas_items[idx].factura_path = "/storage/recibos/userId_" + (item.user_id ?? this.effectiveUserId) + "/" + data.factura_url;
                        }
                    }
                }
            } catch (err) {
                const msg = err.response?.data?.error || "Error al regenerar el PDF.";
                if (this.$toast) this.$toast.error(msg);
                else console.error(msg);
            } finally {
                this.regenerandoPdfId = null;
            }
        },
        openDialog(item) {
            this.email_dialog = true;
            console.log("item", item);
            this.selected_item = item;
            const propietarioId = item?.user_id ?? this.effectiveUserId;
            this.dialogUserId = propietarioId;
            this.url_files = {
                presupuesto_url: item?.presupuesto_url
                    ? `userId_${propietarioId}/${item.presupuesto_url}`
                    : null,
                factura_url: item?.factura_url
                    ? `userId_${propietarioId}/${item.factura_url}`
                    : null,
                nota_url: item?.nota_url
                    ? `userId_${propietarioId}/${item.nota_url}`
                    : null,
            };
        },
        closeDialog() {
            this.email_dialog = false;
            this.dialogUserId = null;
        },
        printFacturas(resumen) {
            axios
                .post(
                    `api/print-factura?tipo=${this.$route.meta.tipo}${
                        resumen ? "&resumen=true" : ""
                    }`,
                    {
                        elementos: this.selected,
                    }
                )
                .then((res) => {
                    console.log("res", res.data);
                    // Open the URL in a new tab
                    window.open("/" + res.data, "_blank");
                });
        },
        SendMailLote() {
            this.email.destinatario = this.destinatarios
                .split(",")
                .map((email) => email.trim());
            this.email.cc = this.destinatariosCC
                .split(",")
                .map((email) => email.trim());
            this.email.cco = this.destinatariosCCO
                .split(",")
                .map((email) => email.trim());

            axios
                .post(`api/factura/mails`, {
                    elementos: this.selected,
                    body: this.lotetext,
                    data_email: this.email,
                })
                .then((res) => {
                    // Open the URL in a new tab
                    //window.open(res.data, "_blank");
                    this.modalConfirm = false;
                    $toast.sucs("Factura Enviadas con exito");
                    this.getFacturas();
                });
        },
        getFacturas() {
            let busqueda = `?${this.$route.meta.tipo}=true`;
            if (this.filter.fecha_desde) {
                busqueda += "&fecha_desde=" + this.filter.fecha_desde;
            }
            if (this.filter.fecha_hasta) {
                busqueda += "&fecha_hasta=" + this.filter.fecha_hasta;
            }
            // if (this.filter.search) {
            //     busqueda += "&search=" + this.filter.search;
            // }
            axios
                .get(`api/get-facturas${busqueda}`)
                .then(
                    (res) => {
                        this.facturas_items = res.data;
                    },
                    (err) => {
                        $toast.error("Error consultando facturas");
                    }
                );
        },
        deleteFactura(item) {
            this.confirmDialog.modelValue = false;
            axios.get(`api/delete-factura/${item.id}`).then(
                (res) => {
                    this.facturas.splice(this.facturas.indexOf(item), 1);
                    $toast.sucs("Factura eliminada");
                },
                (err) => {
                    $toast.error("Error eliminando factura");
                }
            );
        },
        showConfirmDialogDeleteFactura(item) {
            this.confirmDialog.title = "¿Esta seguro de continuar?";
            this.confirmDialog.text =
                "Esta acción eliminará el registro seleccionado.";
            this.confirmDialog.color = "warning";
            this.confirmDialog.confirmAction = () => this.deleteFactura(item);
            this.confirmDialog.cancelAction = () =>
                this.cancelConfirmDialogDeleteFactura();
            this.confirmDialog.modelValue = true;
        },
        cancelConfirmDialogDeleteFactura() {
            this.confirmDialog.modelValue = false;
        },
        getMailBody() {
            axios.get(`api/factura/mails/body`).then((res) => {
                this.defaultLoteText = res.data.body;
            });
        },
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('ListaFacturas: Cliente cambiado, recargando facturas...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.facturas_items = [];
            this.selected = [];
            this.getFacturas();
        },
    },
    computed: {
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
        facturas: function () {
            let facturas = this.facturas_items;
            if (this.filter.pendientes) {
                facturas = facturas.filter((ele) => !ele.pagado);
            }
            if (this.filter.servicio) {
                facturas = facturas.filter((ele) =>
                    ele.servicios.find(
                        (serv) => serv.id_servicio == this.filter.servicio
                    )
                );
            }
            return facturas;
        },
        isloading: function () {
            return this.$store.getters.getloading;
        },
    },
};
</script>
