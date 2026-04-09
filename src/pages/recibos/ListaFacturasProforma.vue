<template>
    <VCard
        class="pb-10"
        title="Lista de facturas proforma">
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
                        :to="`/guardar-recibo?tipo=facturaproforma`"
                        >Nuevo</VBtn
                    >
                </VCol>
            </VRow>
        </div>

        <loader v-if="isloading"></loader>

        <VDataTable
            :headers="headers"
            :items="facturas"
            :search="search"
            item-key="id"
            class="elevation-1 mt-2">
            <template v-slot:item.archivo="{item}">
                <VIcon
                    v-if="item.nombre_factura != null"
                    medium
                    class="mr-2 cursor-pointer"
                    color="#FF4C51"
                    :class="{ 'opacity-50': regenerandoPdfId === item.id }"
                    :disabled="regenerandoPdfId === item.id"
                    @click="verPdf(item)">
                    mdi mdi-file-pdf-box
                </VIcon>
            </template>
            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="`/guardar-recibo?id=${item.id}&tipo=facturaproforma`"
                    class="action-buttons">
                    <VIcon
                        small
                        class="mr-2"
                        color="grey-600"
                        >ri-pencil-line</VIcon
                    >
                </RouterLink>

                <VIcon
                    @click="showConfirmDialogDeleteFactura(item)"
                    small
                    class="mr-2"
                    color="grey-600"
                    >ri-delete-bin-line</VIcon
                >
            </template>
        </VDataTable>
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

export default {
    mixins: [gestorClienteMixin],
    components: {
        ConfirmDialogVue,
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
            search: "",
            facturas: [],
            regenerandoPdfId: null,
            headers: [
                {
                    title: "Nro. Fact. Proforma",
                    align: "left",
                    value: "nro_factura_prof",
                },
                {title: "Fecha", value: "fecha"},
                {title: "Cliente", value: "cliente.nombre"},
                {title: "total", value: "total"},
                {title: "PDF", value: "archivo"},
                {title: "Acciones", value: "action", sortable: false},
            ],
        };
    },
    created() {
        this.getFacturasProforma();
    },
    methods: {
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
                        const idx = this.facturas.findIndex((f) => f.id === item.id);
                        if (idx !== -1) {
                            this.facturas[idx].factura_url = data.factura_url;
                            this.facturas[idx].factura_path = "/storage/recibos/userId_" + (item.user_id ?? this.effectiveUserId) + "/" + data.factura_url;
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
        getFacturasProforma() {
            axios
                .get(`api/get-facturas-proforma`)
                .then(
                    (res) => {
                        this.facturas = res.data;
                    },
                    (err) => {
                        $toast.error("Error consultando facturas Proforma");
                    }
                );
        },

        deleteFacturaProforma(item) {
            this.confirmDialog.modelValue = false;
            axios.get(`api/delete-factura-proforma/${item.id}`).then(
                (res) => {
                    this.facturas.splice(this.facturas.indexOf(item), 1);
                    $toast.sucs("Factura Proforma eliminada");
                },
                (err) => {
                    $toast.error("Error eliminando factura Proforma");
                }
            );
        },
        showConfirmDialogDeleteFactura(item) {
            this.confirmDialog.title = "¿Esta seguro de continuar?";
            this.confirmDialog.text =
                "Esta acción eliminará el registro seleccionado.";
            this.confirmDialog.color = "warning";
            this.confirmDialog.confirmAction = () =>
                this.deleteFacturaProforma(item);
            this.confirmDialog.cancelAction = () =>
                this.cancelConfirmDialogDeleteFactura();
            this.confirmDialog.modelValue = true;
        },
        cancelConfirmDialogDeleteFactura() {
            this.confirmDialog.modelValue = false;
        },
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('ListaFacturasProforma: Cliente cambiado, recargando facturas proforma...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.facturas = [];
            this.getFacturasProforma();
        },
    },

    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
    },
};
</script>
