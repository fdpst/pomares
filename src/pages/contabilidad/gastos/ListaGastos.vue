<template>
    <VCard class="pb-10" title="Lista de gastos">
        <div class="ps-5 pe-5 pb-5">
            <VRow>
                <VCol cols="12" md="4">
                    <VTextField
                        prepend-icon="ri-user-search-fill"
                        v-model="search"
                        label="Código / Importe"
                    ></VTextField>
                </VCol>

                <VCol cols="12" md="4">
                    <VTextField
                        prefix="€"
                        readonly
                        disabled
                        v-model="total"
                        label="Total"
                    ></VTextField>
                </VCol>

                <VCol cols="12" md="4" class="text-end">
                    <VBtn
                        rounded
                        depressed
                        color="primary"
                        class="mt-1"
                        :to="`/guardar-gasto`"
                        >Nuevo</VBtn
                    >
                </VCol>
            </VRow>
        </div>

        <loader v-if="isloading"></loader>

        <VDataTable
            :headers="headers"
            :items="gastos"
            :search="search"
            disable-pagination
            hide-default-footer
            item-key="id"
            class="elevation-1 mt-2"
        >
            <template v-slot:item.fecha="{ item }">
                {{ formatDateEs(item.fecha) }}
            </template>
            <template v-slot:item.importe="{ item }">
                {{ formatPrice(item.importe) }}€
            </template>
            <template v-slot:item.tipo="{ item }">
                {{ item.tipo.nombre }}
            </template>

            <template v-slot:item.action="{ item }">
                <a
                    v-if="item.archivo != null"
                    target="_blank"
                    @click="down(item)"
                >
                    <VIcon medium color="#5142A6" class="mr-2">
                        ri-download-cloud-fill
                    </VIcon>
                </a>

                <RouterLink
                    :to="`/update-gasto/` + item.id"
                    class="action-buttons"
                >
                    <VIcon small class="mr-2" color="grey-600">
                        ri-pencil-line
                    </VIcon>
                </RouterLink>

                <VIcon
                    @click="mostrarModalEliminar(item)"
                    small
                    class="mr-2"
                    color="red"
                >
                    ri-delete-bin-line
                </VIcon>
            </template>
        </VDataTable>
    </VCard>

    <ConfirmDialog
        v-model="modalEliminar"
        @cancel="closeModal"
        @confirm="deleteGasto"
    />
</template>

<script>
import { date_mixin } from "../mixins/date_mixin";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import rangoFechas from "../rangoFechas.vue";

export default {
    mixins: [date_mixin, gestorClienteMixin],

    components: {
        rangoFechas,
    },

    data() {
        return {
            modalEliminar: false,
            item: "",
            url: `api/get-gastos`,
            search: "",
            gastos: [],
            headers: [
                {
                    title: "Código",
                    align: "left",
                    value: "codigo",
                },
                {
                    title: "Fecha",
                    value: "fecha",
                    filterable: false,
                },
                {
                    title: "Importe",
                    value: "importe",
                },
                {
                    title: "Tipo",
                    value: "tipo.nombre",
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ],
        };
    },
    mounted() {
        // this.$emit('hacer_busqueda')
        this.getGatos();
    },
    methods: {
        getGatos() {
            axios.get(this.url).then(
                (res) => {
                    this.gastos = res.data.gastos.data;
                },
                (res) => {
                    $toast.error("Error consultando Gasto");
                }
            );
        },

        setGastos(data) {
            if (data.length > 0) {
                this.gastos = data;
                return;
            }
            this.gastos = [];
            $toast.sucs("No se encontraron registros");
        },
        mostrarModalEliminar(item) {
            this.modalEliminar = true;
            this.item = item;
        },
        closeModal() {
            this.modalEliminar = false;
            this.item = "";
        },
        deleteGasto(item) {
            this.modalEliminar = false;
            axios.get(`api/delete-gasto/${this.item.id}`).then(
                (res) => {
                    this.gastos.splice(this.gastos.indexOf(this.item), 1);
                    $toast.sucs("Gasto eliminado con exito");
                },
                (err) => {
                    $toast.error("Error Eliminando gasto");
                }
            );
        },

        down(item) {
            let downloadPath =
                "/storage/documentos/userId_" +
                this.effectiveUserId +
                "/factura_recibidas/" +
                item.archivo;
            this.downloadFiles(downloadPath, item.archivo);
        },
        downloadFiles(url, filename) {
            fetch(url).then(function (t) {
                return t.blob().then((b) => {
                    var a = document.createElement("a");
                    a.href = URL.createObjectURL(b);
                    a.setAttribute("download", filename);
                    a.click();
                });
            });
        },
        onClienteChanged(event) {
            console.log('ListaGastos: Cliente cambiado, recargando gastos...', event.detail);
            this.gastos = [];
            this.getGatos();
        }
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        total: function () {
            let total = this.gastos.reduce((acc, gasto) => {
                return acc + gasto.importe;
            }, 0);

            return parseFloat(total).toFixed(2);
        },
        userId() {
            return localStorage.getItem("user_id");
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
