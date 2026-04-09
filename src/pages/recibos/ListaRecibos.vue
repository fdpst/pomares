<template>
    <VCard class="pb-10" title="Lista de presupuestos">
        <loader v-if="isloading || loadingRecibos"></loader>
        <ConfirmDialog
            v-model="modalEliminar"
            @cancel="closeModal"
            @confirm="deleteRecibo"
        />

        <div class="ps-5 pe-5 pb-5">
            <VRow>
                <VCol cols="12" md="8">
                    <VTextField
                        prepend-icon="ri-user-search-fill"
                        v-model="search"
                        label="Búsqueda"
                    ></VTextField>
                </VCol>

                <VCol cols="12" md="4" class="text-end">
                    <VBtn
                        rounded
                        depressed
                        color="primary"
                        class="mt-1"
                        :to="`/guardar-recibo?tipo=presupuesto`"
                        >Nuevo</VBtn
                    >
                </VCol>
            </VRow>
        </div>

        <VDataTable
            :headers="headers"
            :items="recibos"
            :search="search"
            item-key="id"
            class="elevation-1 mt-2"
        >
            <template v-slot:item.fecha="{ item }">
                {{ formatDateEs(item.fecha) }}
            </template>
            <template v-slot:item.total="{ item }">
                {{ formatPrice(item.total) }}€
            </template>

            <template v-slot:item.archivo="{ item }">
                <a
                    v-if="item.nombre_presupuesto != 'false'"
                    target="_blank"
                    :href="'/' + item.presupuesto_path"
                >
                    <VIcon medium color="#FF4C51" class="mr-2">
                        mdi mdi-file-pdf-box
                    </VIcon>
                </a>

                <a
                    v-if="item.nombre_factura != null"
                    target="_blank"
                    :href="'/' + item.factura_path"
                >
                    <VIcon medium color="#FF4C51" class="mr-2">
                        mdi mdi-file-pdf-box
                    </VIcon>
                </a>
            </template>

            <template v-slot:item.action="{ item }">
                <RouterLink
                    :to="{
                        path: '/guardar-recibo',
                        query: { id: item.id, tipo: 'presupuesto' },
                    }"
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
                    color="grey-600"
                >
                    ri-delete-bin-line
                </VIcon>
            </template>
        </VDataTable>
    </VCard>
</template>

<script>
import { formatPrice } from "@/@core/utils/formatters";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';

export default {
    mixins: [gestorClienteMixin],
    data() {
        return {
            modalEliminar: false,
            item: "",
            search: "",
            recibos: [],
            loadingRecibos: false,
            headers: [
                {
                    title: "Nro.Presupuesto",
                    align: "left",
                    value: "nro_presupuesto",
                },
                {
                    title: "Fecha",
                    value: "fecha",
                },
                {
                    title: "Cliente",
                    value: "cliente",
                },
                {
                    title: "total",
                    value: "total",
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
        };
    },
    created() {
        this.getRecibos();
    },
    methods: {
        getRecibos() {
            // No pasar el user_id en la URL, el backend lo determinará automáticamente
            this.loadingRecibos = true;
            axios
                .get(`api/get-recibos`)
                .then(
                    (res) => {
                        this.recibos = res.data;
                        this.loadingRecibos = false;
                    },
                    (err) => {
                        $toast.error("Error consultando presupuestos");
                        this.loadingRecibos = false;
                    }
                );
        },
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('ListaRecibos: Cliente cambiado, recargando presupuestos...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.recibos = [];
            this.getRecibos();
        },
        mostrarModalEliminar(item) {
            this.modalEliminar = true;
            this.item = item;
        },
        closeModal() {
            this.modalEliminar = false;
            this.item = "";
        },
        deleteRecibo(item) {
            this.modalEliminar = false;
            axios.get(`api/delete-recibo/${this.item.id}`).then(
                (res) => {
                    this.recibos.splice(this.recibos.indexOf(this.item), 1);
                    $toast.sucs("Presupuesto eliminado");
                    this.item = "";
                },
                (err) => {
                    $toast.error("Error eliminando presupuesto");
                }
            );
        },
        formatPrice,
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
    },
};
</script>
