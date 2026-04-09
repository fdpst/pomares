<template>
    <VCard
        class="pb-10"
        title="Lista de facturas recibidas">
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
                        :to="'/form-facturas-recibidas'"
                        >Nuevo</VBtn
                    >
                </VCol>
            </VRow>
        </div>

        <loader v-if="isloading"></loader>

        <VDataTable
            :headers="headers"
            :items="facturaRecibidas"
            :search="search"
            item-key="id"
            class="elevation-1 mt-2">
            <template v-slot:item.imagen="{item}">
                <VIcon
                    @click="callDown(item)"
                    medium
                    color="#5142A6"
                    class="mr-2">
                    ri-download-cloud-fill
                </VIcon>
            </template>
            <template v-slot:item.nro_factura="{item}">
                <span v-if="item.nro_factura != null">
                    {{
                        item.nro_factura == null || item.nro_factura == "null"
                            ? "Sin información"
                            : item.nro_factura
                    }}
                </span>
            </template>
            <template v-slot:item.fecha="{item}">
                <span v-if="item.fecha != null">
                    {{ formatDateEs(item.fecha) }}
                </span>
            </template>
            <template v-slot:item.total="{item}">
                <span v-if="item.total != null">
                    {{ formatPrice(item.total) }}€
                </span>
            </template>
            <template v-slot:item.action="{item}">
                <RouterLink
                    :to="'/form-facturas-recibidas-update/' + item.id"
                    class="action-buttons">
                    <VIcon
                        small
                        class="mr-2"
                        color="grey-600">
                        ri-pencil-line
                    </VIcon>
                </RouterLink>

                <VIcon
                    @click="mostrarModalEliminar(item)"
                    small
                    class="mr-2"
                    color="red">
                    ri-delete-bin-line
                </VIcon>

                <VIcon
                    @click="mostrarModalDuplicar(item)"
                    small
                    class="mr-2"
                    color="orange"
                    title="Duplicar factura">
                    mdi mdi-content-duplicate
                </VIcon>
            </template>
        </VDataTable>
    </VCard>

    <ConfirmDialog
        v-model="modalEliminar"
        @cancel="closeModal"
        @confirm="deleteFac"
        color="primary" />

    <ConfirmDialog
        v-model="modalDuplicar"
        color="info"
        text="¿Está seguro de que desea crear una nueva factura con los datos de la factura seleccionada?"
        @cancel="modalDuplicar = false"
        @confirm="
            () => {
                $router.push('/form-facturas-recibidas-update/' + item.id);
            }
        " />
</template>

<script>
import {localizePrice} from "@/components/Transformations";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';

export default {
    mixins: [gestorClienteMixin],
    data() {
        return {
            modalEliminar: false,
            modalDuplicar: false,
            item: "",
            search: "",
            facturaRecibidas: [],
            headers: [
                {
                    title: "NRO.FACTURA",
                    value: "nro_factura",
                },
                {
                    title: "Fecha",
                    value: "fecha",
                },
                {
                    title: "Proveedor",
                    value: "proveedor.nombre",
                },
                {
                    title: "Descripción",
                    value: "descripcion",
                },

                {
                    title: "Archivos",
                    value: "imagen",
                },
                {
                    title: "Total",
                    value: "total",
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
        this.getFactRecibidas();
    },
    methods: {
        localizePrice,
        callDown(doc) {
            let imagenes = JSON.parse(doc.imagen);
            let originaName = window.location.origin + "/";
            const ownerId = doc?.user_id ?? this.effectiveUserId;
            let pathServer = "storage/recibos/userId_" + ownerId + "/";
            let pathDoc = "";
            let documentImagen = "";
            for (var r = 0; r < imagenes?.length; r++) {
                pathDoc = originaName + pathServer + imagenes[r];
                documentImagen = imagenes[r];
                this.downloadFiles(pathDoc, documentImagen);
            }
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
        getFactRecibidas() {
            axios
                .get(`api/facturas-recibidas`)
                .then(
                    (res) => {
                        this.facturaRecibidas = res.data.facturaRecibidas;
                        // console.log(this.facturaRecibidas)
                    },
                    (err) => {
                        $toast.error("Error consultando facturaRecibidas");
                    }
                );
        },
        mostrarModalEliminar(item) {
            this.modalEliminar = true;
            this.item = item;
        },
        mostrarModalDuplicar(item) {
            this.modalDuplicar = true;
            this.item = item;
        },
        closeModal() {
            this.modalEliminar = false;
            this.item = "";
        },
        deleteFac(item) {
            this.modalEliminar = false;
            axios.post(`api/facturas-recibidas-delete/${this.item.id}`).then(
                (res) => {
                    this.getFactRecibidas();
                    $toast.sucs("factura eliminada");
                    this.item = "";
                },
                (err) => {
                    $toast.error("Error eliminando factura");
                }
            );
        },
        descargarArchivos(item) {
            //quitamos todos los caracteres que no necesitamos
            let archivos = item.imagen.replaceAll('"', "");
            archivos = archivos.replaceAll("[", "");
            archivos = archivos.replaceAll("]", "");

            //convertimos en array
            let array_archivos = archivos.split(",");
            //descargamos todos los archivos
            array_archivos.forEach((element) => {
                var archivo = document.createElement("a");
                archivo.setAttribute(
                    "href",
                    "/storage/documentos/" +
                        "userId_" +
                        (item?.user_id ?? this.effectiveUserId) +
                        "recibidos/" +
                        element
                );
                archivo.setAttribute("download", element);
                document.body.appendChild(archivo);
                archivo.click();
            });
        },
        /*duplicarFacturaRecibida(){
      console.log('item', this.item)
      axios
        .post(`api/duplicar-factura-recibida`, this.item)
        .then(
          (res) => {
            $toast.sucs("Factura duplicada con exito");
            this.getFactRecibidas();
            this.modalDuplicar = false
          },
          (err) => {
            $toast.error("Error consultando servicios");
          }
        );
    }*/
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('ListaFacturasRecibidas: Cliente cambiado, recargando facturas recibidas...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.facturaRecibidas = [];
            this.getFactRecibidas();
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
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
    },
};
</script>
