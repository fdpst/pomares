<template>
    <VCard class="pb-10" title="Lista de clientes">
        <div class="ps-5 pe-5 pb-5">
            <VRow>
                <VCol cols="12" md="8">
                    <VTextField
                        prepend-icon="ri-user-search-fill"
                        v-model="search"
                        label="Búsqueda"
                    ></VTextField>
                </VCol>

                <VCol cols="12" md="4" class="text-end d-flex flex-wrap justify-end gap-2">
                    <input
                        ref="importInput"
                        type="file"
                        accept=".xlsx,.xls,.csv"
                        class="d-none"
                        @change="handleImportFile"
                    />
                    <VBtn
                        rounded
                        variant="tonal"
                        color="secondary"
                        class="mt-1"
                        prepend-icon="ri-download-2-line"
                        :loading="exporting"
                        @click="exportClientes"
                        >Exportar
                    </VBtn>
                    <VBtn
                        rounded
                        variant="outlined"
                        color="secondary"
                        class="mt-1"
                        prepend-icon="ri-upload-2-line"
                        :loading="importing"
                        @click="triggerImport"
                        >Importar
                    </VBtn>
                    <VBtn
                        rounded
                        depressed
                        color="primary"
                        class="mt-1"
                        :to="{ name: `guardar-cliente` }"
                        >Nuevo
                    </VBtn>
                </VCol>
            </VRow>
        </div>

        <loader v-if="isloading || loadingClientes"></loader>

        <VDataTableServer
            :headers="headers"
            :items="clientes || []"
            :search="search"
            item-key="id"
            class="elevation-1 mt-2"
            v-model:items-per-page="pagination.itemsPerPage"
            v-model:page="pagination.page"
            :items-length="totalClientes || 0"
            @update:options="updateOptions"
        >
            <template v-slot:item.activo="{ item }">
                <VChip v-if="item.activo" class="chip-activo"> Activo </VChip>
                <VChip v-else color="grey-600"> Inactivo </VChip>
            </template>
            <template v-slot:item.action="{ item }">
                <RouterLink
                    :to="`/guardar-cliente?id=${item.id}`"
                    class="action-buttons"
                >
                    <VIcon color="grey-600" icon="ri-pencil-line" />
                </RouterLink>
                <VIcon @click="mostrarModalEliminar(item)" small class="mr-2">
                    ri-delete-bin-line
                </VIcon>
            </template>

            <!-- Pagination -->
            <template #bottom>
                <VDivider />

                <div class="d-flex justify-end flex-wrap gap-x-6 px-2 py-1">
                    <div
                        class="d-flex align-center gap-x-2 text-medium-emphasis text-base"
                    >
                        Filas por página:
                        <VSelect
                            v-model="pagination.itemsPerPage"
                            class="per-page-select"
                            variant="plain"
                            :items="[10, 20, 25, 50, 100]"
                        />
                    </div>

                    <p
                        class="d-flex align-center text-base text-high-emphasis me-2 mb-0"
                    >
                        {{
                            paginationMeta(
                                {
                                    page: pagination.page,
                                    itemsPerPage: pagination.itemsPerPage,
                                },
                                totalClientes
                            )
                        }}
                    </p>

                    <div class="d-flex gap-x-2 align-center me-2">
                        <VBtn
                            class="flip-in-rtl"
                            icon="ri-arrow-left-s-line"
                            variant="text"
                            density="comfortable"
                            color="high-emphasis"
                            :disabled="pagination.page <= 1"
                            @click="
                                pagination.page <= 1
                                    ? (pagination.page = 1)
                                    : pagination.page--
                            "
                        />

                        <VBtn
                            class="flip-in-rtl"
                            icon="ri-arrow-right-s-line"
                            density="comfortable"
                            variant="text"
                            color="high-emphasis"
                            :disabled="
                                pagination.page >=
                                Math.ceil(
                                    totalClientes / pagination.itemsPerPage
                                )
                            "
                            @click="
                                pagination.page >=
                                Math.ceil(
                                    totalClientes / pagination.itemsPerPage
                                )
                                    ? (pagination.page = Math.ceil(
                                          totalClientes /
                                              pagination.itemsPerPage
                                      ))
                                    : pagination.page++
                            "
                        />
                    </div>
                </div>
            </template>
        </VDataTableServer>
    </VCard>

    <ConfirmDialog
        v-model="modalEliminar"
        color="primary"
        @cancel="closeModal"
        @confirm="deleteCliente"
    />
</template>

<script>
import debounce from "lodash/debounce";
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js'

export default {
    mixins: [gestorClienteMixin],
    
    data() {
        return {
            modalEliminar: false,
            item: "",
            search: "",
            clientes: [],
            loadingClientes: false,
            exporting: false,
            importing: false,
            importSummary: null,
            headers: [
                {
                    title: "N°",
                    value: "nro_cliente",
                },
                {
                    title: "CIF/DNI",
                    value: "dni",
                },
                {
                    title: "Nombre comercial",
                    value: "nombre_comercial",
                },
                {
                    title: "Nombre fiscal",
                    value: "nombre",
                },
                {
                    title: "Email",
                    value: "email",
                },
                {
                    title: "Teléfono",
                    value: "telefono",
                },
                {
                    title: "Provincia",
                    value: "provincia",
                },
                {
                    title: "Fecha alta",
                    value: "fecha_alta",
                },
                {
                    title: "Estado",
                    value: "activo",
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ],
            totalClientes: 0,
            pagination: {
                page: 1,
                itemsPerPage: 10,
                sortBy: [],
                orderBy: "",
            },
        };
    },

    created() {
        this.getClientes();
    },

    methods: {
        getClientes() {
            // No pasar el user_id en la URL, el backend lo determinará automáticamente
            this.loadingClientes = true;
            axios
                .get(`api/get-clientes`, {
                    params: {
                        amount: this.pagination.itemsPerPage ?? 10,
                        page: this.pagination.page ?? 1,
                        sortBy: this.pagination.sortBy,
                        orderBy: this.pagination.orderBy,
                        search: this.search,
                    },
                })
                .then(
                    (res) => {
                        this.clientes = res.data.data || [];
                        this.totalClientes = res.data.total || 0;
                        this.loadingClientes = false;
                    },
                    (err) => {
                        $toast.error("Error consultando clientes");
                        this.clientes = [];
                        this.totalClientes = 0;
                        this.loadingClientes = false;
                    }
                );
        },
        
        // Método llamado cuando cambia el cliente seleccionado
        onClienteChanged(event) {
            console.log('Cliente cambiado, recargando clientes...', event.detail);
            // Limpiar la lista mientras se cargan los nuevos datos
            this.clientes = [];
            this.totalClientes = 0; // Asegurar que sea un número, no undefined
            // Resetear a la primera página
            this.pagination.page = 1;
            this.getClientes();
        },
        updateOptions(options) {
            this.pagination.itemsPerPage = options.itemsPerPage;
            this.pagination.page = options.page;
            this.pagination.sortBy = options.sortBy[0]?.key;
            this.pagination.orderBy = options.sortBy[0]?.order;
        },
        mostrarModalEliminar(item) {
            this.modalEliminar = true;
            this.item = item;
        },
        closeModal() {
            this.modalEliminar = false;
            this.item = "";
        },
        deleteCliente() {
            this.modalEliminar = false;
            axios.get(`api/delete-cliente/${this.item.id}`).then(
                (res) => {
                    this.clientes.splice(this.clientes.indexOf(this.item), 1);
                    $toast.sucs("Cliente eliminado");
                    this.item = "";
                },
                (err) => {
                    $toast.error("Error eliminando cliente");
                }
            );
        },
        exportClientes() {
            if (this.exporting) return;

            this.exporting = true;
            axios
                .get(`api/clientes/export`, {
                    params: {
                        search: this.search || undefined,
                    },
                    responseType: "blob",
                })
                .then((response) => {
                    const contentDisposition =
                        response.headers["content-disposition"];
                    let filename = "clientes.xlsx";

                    if (contentDisposition) {
                        const matches = /filename="?([^"]+)"?/.exec(
                            contentDisposition
                        );
                        if (matches && matches[1]) {
                            filename = matches[1];
                        }
                    }

                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", filename);
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);
                    $toast.sucs("Exportación generada correctamente");
                })
                .catch(() => {
                    $toast.error("Error exportando clientes");
                })
                .finally(() => {
                    this.exporting = false;
                });
        },
        triggerImport() {
            if (this.importing) return;
            if (this.$refs.importInput) {
                this.$refs.importInput.click();
            }
        },
        handleImportFile(event) {
            const file = event?.target?.files?.[0];
            if (!file) {
                return;
            }

            const formData = new FormData();
            formData.append("file", file);
            this.importing = true;

            axios
                .post(`api/clientes/import`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((res) => {
                    this.importSummary = res?.data?.summary || null;
                    const created = this.importSummary?.created ?? 0;
                    const updated = this.importSummary?.updated ?? 0;
                    $toast.sucs(
                        `Importación completada (${created} nuevos, ${updated} actualizados)`
                    );
                    this.getClientes();
                })
                .catch(() => {
                    $toast.error("Error importando clientes");
                })
                .finally(() => {
                    this.importing = false;
                    if (this.$refs.importInput) {
                        this.$refs.importInput.value = null;
                    }
                });
        },
    },
    watch: {
        search: {
            handler() {
                debounce(() => {
                    this.getClientes();
                }, 500)();
            },
        },
        // Un solo watcher para el objeto pagination
        pagination: {
            handler() {
                this.getClientes();
            },
            deep: true,
        },
    },
    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
    },
};
</script>
