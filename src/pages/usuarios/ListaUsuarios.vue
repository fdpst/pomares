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

                    <VBtn @click="cambiar_anio"> Cambiar Año Fiscal </VBtn>
                </div>

                <!-- <VRow class="mt-5">
          <VCol md="6" cols="12">
            <VTextField 
              single-line 
              outlined 
              color="success" 
              append-icon="ri-user-search-fill" 
              v-model="search" 
              label="Buscar Usuario..."
            />
          </VCol>
        </VRow> -->
            </VCardText>

            <VDataTableServer
                dense
                :headers="headers"
                :items="Usuarios"
                :search="search"
                item-key="id"
                class="elevation-2 mt-3"
                v-model:items-per-page="options.itemsPerPage"
                v-model:page="options.page"
                :items-length="totalUsuarios"
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
                        v-if="canResetEmployeePassword(item)"
                        small
                        class="mr-2"
                        color="info"
                        title="Resetear contraseña y enviar email de acceso"
                        @click="openDialogResetPassword(item)"
                    >
                        ri-mail-send-line
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
                                    totalUsuarios
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
                                        totalUsuarios / options.itemsPerPage
                                    )
                                "
                                @click="
                                    options.page >=
                                    Math.ceil(
                                        totalUsuarios / options.itemsPerPage
                                    )
                                        ? (options.page = Math.ceil(
                                              totalUsuarios /
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
                type="usuario"
                @close="closeFormUsuarios"
            />
        </VDialog>

        <dialog-delete-user
            :dialogDeleteUser="dialogDeleteUser"
            :closedialogDeleteUser="closedialogDeleteUser"
            :captureItem="captureItem"
        >
        </dialog-delete-user>

        <VDialog v-model="dialogResetPassword" max-width="450" persistent>
            <VCard title="Restablecer contraseña">
                <VCardText>
                    <p v-if="itemToReset">
                        ¿Enviar nueva contraseña por email a <strong>{{ itemToReset.name }}</strong> ({{ itemToReset.email }})?
                    </p>
                    <p class="text-caption text-medium-emphasis mt-2">
                        Se generará una contraseña nueva y se enviará al correo del usuario. Aplica a perfiles Administrador, Gestor y Empleado.
                    </p>
                </VCardText>
                <VCardActions>
                    <VSpacer />
                    <VBtn color="secondary" variant="text" @click="closeDialogResetPassword">Cancelar</VBtn>
                    <VBtn color="primary" :loading="sendingResetPassword" @click="confirmResetEmployeePassword">Enviar</VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
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
            Usuarios: [],
            totalUsuarios: 0,
            options: {
                page: 1,
                itemsPerPage: 15,
            },
            dialogDeleteUser: false,
            captureItem: {},
            isFormUsuariosVisible: false,
            userSelected: null,
            dialogResetPassword: false,
            itemToReset: null,
            sendingResetPassword: false,
        };
    },

    created() {
        this.getUsuarios();
    },

    methods: {
        cambiar_anio() {
            axios.get("api/cambiar-anio-fiscal").then((res) => {
                this.anio_dialog = false;
            });
        },

        getUsuarios() {
            // Filtrar administradores (1), gestores (3) y empleados (4)
            axios
                .get(`api/get-usuarios`, {
                    params: {
                        role: [1, 3, 4],
                        itemsPerPage: this.options.itemsPerPage,
                        page: this.options.page,
                        search: this.search,
                    },
                })
                .then(
                    (res) => {
                        this.Usuarios = res.data.users.data;
                        this.totalUsuarios = res.data.users.total;

                        for (let i = 0; i < this.Usuarios.length; i++) {
                            const element = this.Usuarios[i];
                            element.created_at = new Date(
                                element.created_at
                            ).toLocaleDateString();
                        }
                    },
                    (err) => {
                        $toast.error("Error consultando Usuario");
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

        openDialogResetPassword(item) {
            this.itemToReset = item;
            this.dialogResetPassword = true;
        },

        closeDialogResetPassword() {
            this.dialogResetPassword = false;
            this.itemToReset = null;
        },

        confirmResetEmployeePassword() {
            if (!this.itemToReset) return;
            this.sendingResetPassword = true;
            axios
                .post(`api/reset-employee-password/${this.itemToReset.id}`)
                .then((res) => {
                    this.closeDialogResetPassword();
                    $toast.sucs(res.data.message || "Contraseña restablecida y email enviado correctamente.");
                    this.getUsuarios();
                })
                .catch((err) => {
                    const msg = err.response?.data?.error || err.response?.data?.message?.[0] || "Error al resetear la contraseña.";
                    $toast.error(msg);
                })
                .finally(() => {
                    this.sendingResetPassword = false;
                });
        },

        closedialogDeleteUser() {
            this.dialogDeleteUser = false;
            this.getUsuarios();
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
                        $toast.error("Error al cargar el usuario");
                        console.error(err);
                    });
            } else {
                this.userSelected = null;
                this.isFormUsuariosVisible = true;
            }
        },
        closeFormUsuarios() {
            this.isFormUsuariosVisible = false;
            this.userSelected = null;
            this.getUsuarios();
        },

        canResetEmployeePassword(item) {
            // El rol puede venir del store o de localStorage (el login guarda en localStorage)
            const storeUser = this.$store.getters.getuser;
            const currentRole = storeUser?.role != null && storeUser.role !== 0
                ? Number(storeUser.role)
                : Number(localStorage.getItem('role') || 0);
            if (currentRole !== 1) return false;
            // Solo administrador (1) puede ver el icono; aplica a Administrador (1), Gestor (3) y Empleado (4). No Cliente (2).
            const role = Number(item.role);
            return role === 1 || role === 3 || role === 4;
        },
    },

    watch: {
        options: {
            handler() {
                this.getUsuarios();
            },
            deep: true,
        },
        search: {
            handler() {
                this.getUsuarios();
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
