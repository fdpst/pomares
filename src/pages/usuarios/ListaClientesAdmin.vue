<template>
    <section>
        <div class="text-center">
            <loader v-if="isloading"></loader>
        </div>

        <VCard title="Filtros" class="mb-6">
            <VCardText>
                <div class="app-user-search-filter d-flex align-center">
                    <!-- 👉 Search  -->
                    <VTextField
                        v-model="search"
                        style="min-width: 200px"
                        placeholder="Buscar"
                        density="compact"
                        class="me-4"
                    />

                    <VBtn class="me-4" @click="openFormUsuarios(null)">
                        Nuevo
                    </VBtn>
                </div>
            </VCardText>

            <VDataTableServer
                dense
                :headers="headers"
                :items="Clientes"
                :search="search"
                item-key="id"
                class="elevation-2 mt-3"
                v-model:items-per-page="options.itemsPerPage"
                v-model:page="options.page"
                :items-length="totalClientes"
                @update:options="onOptionsUpdate"
            >
                <template #item.action="{ item }">
                    <VIcon
                        small
                        class="mr-2"
                        color="grey-600"
                        @click="openFormUsuarios(item)"
                    >
                        ri-pencil-line
                    </VIcon>

                    <VIcon
                        @click="deleteUsuario(item)"
                        small
                        class="mr-2"
                        color="red"
                    >
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
                                v-model="options.itemsPerPage"
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
                                        page: options.page,
                                        itemsPerPage: options.itemsPerPage,
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
                                :disabled="options.page <= 1"
                                @click="
                                    options.page <= 1
                                        ? (options.page = 1)
                                        : options.page--
                                "
                            />

                            <VBtn
                                class="flip-in-rtl"
                                icon="ri-arrow-right-s-line"
                                density="comfortable"
                                variant="text"
                                color="high-emphasis"
                                :disabled="
                                    options.page >=
                                    Math.ceil(
                                        totalClientes / options.itemsPerPage
                                    )
                                "
                                @click="
                                    options.page >=
                                    Math.ceil(
                                        totalClientes / options.itemsPerPage
                                    )
                                        ? (options.page = Math.ceil(
                                              totalClientes /
                                                  options.itemsPerPage
                                          ))
                                        : options.page++
                                "
                            />
                        </div>
                    </div>
                </template>
            </VDataTableServer>
        </VCard>

        <VDialog v-model="isFormUsuariosVisible" max-width="800px">
            <FormUsuarios
                :value="userSelected"
                type="empresa"
                @close="closeFormUsuarios"
            />
        </VDialog>

        <dialog-delete-user
            :dialogDeleteUser="dialogDeleteUser"
            :closedialogDeleteUser="closedialogDeleteUser"
            :captureItem="captureItem"
        >
        </dialog-delete-user>
    </section>
</template>

<script>
import dialogDeleteUser from "./dialogDeleteUser.vue";
import FormUsuarios from "./FormUsuarios.vue";

export default {
    components: {
        "dialog-delete-user": dialogDeleteUser,
        FormUsuarios,
    },

    data() {
        return {
            search: "",
            headers: [
                {
                    title: "Id",
                    value: "id",
                },
                {
                    title: "Nombre",
                    value: "name",
                },
                {
                    title: "Email",
                    value: "email",
                },
                {
                    title: "Perfil",
                    value: "role_str",
                },
                {
                    title: "Fecha",
                    value: "created_at",
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ],
            Clientes: [],
            totalClientes: 0,
            options: {
                page: 1,
                itemsPerPage: 15,
            },
            dialogDeleteUser: false,
            captureItem: {},
            isFormUsuariosVisible: false,
            userSelected: null,
        };
    },

    created() {
        this.getClientes();
    },

    methods: {
        getClientes() {
            // Filtrar solo clientes (role 2)
            axios
                .get(`api/get-usuarios`, {
                    params: {
                        role: 2,
                        itemsPerPage: this.options.itemsPerPage,
                        page: this.options.page,
                        search: this.search,
                    },
                })
                .then(
                    (res) => {
                        this.Clientes = res.data.users.data;
                        this.totalClientes = res.data.users.total;

                        for (let i = 0; i < this.Clientes.length; i++) {
                            const element = this.Clientes[i];
                            element.created_at = new Date(
                                element.created_at
                            ).toLocaleDateString();
                        }
                    },
                    (err) => {
                        $toast.error("Error consultando Clientes");
                    }
                );
        },
        onOptionsUpdate(options) {
            this.options.itemsPerPage = options.itemsPerPage;
            this.options.page = options.page;
        },

        deleteUsuario(item) {
            this.dialogDeleteUser = true;
            this.captureItem = {
                item: item,
            };
        },

        closedialogDeleteUser() {
            this.dialogDeleteUser = false;
            this.getClientes();
        },

        modalDelete() {
            this.dialogDeleteUser = true;
        },

        openFormUsuarios(item) {
            if (item != null) {
                // Cargar el usuario completo desde el backend para obtener las asociaciones
                axios
                    .get(`api/get-usuario-by-id/${item.id}`)
                    .then((res) => {
                        this.userSelected = res.data.user;
                        this.isFormUsuariosVisible = true;
                    })
                    .catch((err) => {
                        $toast.error("Error al cargar el cliente");
                        console.error(err);
                    });
            } else {
                // Crear nuevo cliente con role 2 por defecto
                this.userSelected = { id: null, role: 2 };
                this.isFormUsuariosVisible = true;
            }
        },
        closeFormUsuarios() {
            this.isFormUsuariosVisible = false;
            this.userSelected = null;
            this.getClientes();
        },
    },

    watch: {
        options: {
            handler() {
                this.getClientes();
            },
            deep: true,
        },
        search: {
            handler() {
                this.getClientes();
            },
            debounce: 500,
        },
    },

    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
    },
};
</script>

<style lang="scss">
.app-user-search-filter {
    inline-size: 24.0625rem;
}

.text-capitalize {
    text-transform: capitalize;
}

.user-list-name:not(:hover) {
    color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
}
</style>
