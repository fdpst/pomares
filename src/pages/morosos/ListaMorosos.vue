<template>
    <VCard
        class="pb-10"
        title="Lista de pendientes de pago">
        <div class="ps-5 pe-5 pb-5">
            <VRow>
                <VCol
                    cols="12"
                    md="8">
                    <VTextField
                        prepend-icon="ri-user-search-fill"
                        v-model="search"
                        label="Codigo / Nombre"></VTextField>
                </VCol>
            </VRow>
        </div>

        <loader v-if="isloading"></loader>

        <VDataTable
            :headers="headers"
            :items="morosos"
            :search="search"
            disable-pagination
            hide-default-footer
            item-key="id"
            class="elevation-1 mt-2">
            <template v-slot:item.total="{item}">
                {{ formatPrice(item.total) }}€
            </template>
            <template v-slot:item.pagado="{item}">
                {{ formatPrice(item.pagado) }}€
            </template>
            <template v-slot:item.deuda="{item}">
                {{ formatPrice(item.deuda) }}€
            </template>
            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="{
                        name: 'guardar-ingreso',
                        query: {
                            identi: String(item.nro_factura ?? '').padStart(4, '0') || '0',
                            tipo: item.tipo || 'Factura',
                        },
                    }"
                    class="action-buttons">
                    <VIcon
                        small
                        class="mr-2"
                        color="#5142A6">
                        ri-add-line
                    </VIcon>
                </RouterLink>
                <VIcon
                    small
                    class="mr-2"
                    color="#5142A6"
                    @click="openDialog(item)">
                    ri-mail-line
                </VIcon>
            </template>
        </VDataTable>
        <dialog-pay-reminder
            :isloading="isloading"
            v-on:close_dialog="closeDialog"
            :email_dialog="canDisplayDialogEmail"
            :url_files="url_files"
            tipo="factura"
            :email="selected_item.customer?.email ?? ''"
            :id_factura="selected_item.recibo_id"
            :user_id="effectiveUserId"
            color="primary">
        </dialog-pay-reminder>
    </VCard>
</template>

<script>
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import dialogpayreminder from "./partials/dialog-pay-reminder.vue";

export default {
    mixins: [gestorClienteMixin],
    mounted() {
        console.log(
            "Token de acceso desde index: ",
            useCookie("XSRF-TOKEN").value
        );
    },
    components: {
        "dialog-pay-reminder": dialogpayreminder,
    },
    data() {
        return {
            search: "",
            morosos: [],
            headers: [
                {
                    title: "Tipo",
                    value: "tipo",
                },
                {
                    title: "Nro",
                    value: "nro_factura",
                },
                {
                    title: "Cliente",
                    value: "cliente",
                },
                {
                    title: "Total",
                    value: "total",
                },
                {
                    title: "Pagado",
                    value: "pagado",
                },
                {
                    title: "Deuda",
                    value: "deuda",
                },
                {
                    title: "Fecha",
                    value: "fecha",
                },
                {
                    title: "+ Ingreso",
                    value: "action",
                    sortable: false,
                },
            ],
            selected_item: {
                cliente: {email: null},
            },
            url_files: null,
            canDisplayDialogEmail: false,
        };
    },
    created() {
        this.getMorosos();
    },
    methods: {
        getMorosos() {
            axios
                .get(`api/get-morosos`)
                .then(
                    (res) => {
                        this.morosos = res.data ?? [];
                    },
                    (err) => {
                        this.morosos = [];
                        $toast.error("Error consultando pendientes de pago");
                    }
                );
        },
        closeDialog() {
            this.canDisplayDialogEmail = false;
        },
        openDialog(item) {
            this.canDisplayDialogEmail = true;
            this.selected_item = item;
            const ownerId = item?.recibo?.user_id ?? this.effectiveUserId;
            this.url_files = {
                presupuesto_url:
                    item.recibo?.presupuesto_url
                        ? `userId_${ownerId}/${item.recibo.presupuesto_url}`
                        : null,
                factura_url:
                    item.recibo?.factura_url
                        ? `userId_${ownerId}/${item.recibo.factura_url}`
                        : null,
                nota_url: item.recibo?.nota_url
                        ? `userId_${ownerId}/${item.recibo.nota_url}`
                        : null,
            };
        },
        onClienteChanged(event) {
            console.log('ListaMorosos: Cliente cambiado, recargando pendientes...', event.detail);
            this.morosos = [];
            this.getMorosos();
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
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
};
</script>
