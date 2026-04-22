<template>
    <VCard :title="usuario.id != null ? 'Editar ' + type : 'Crear ' + type">
        <div class="mt-2">
            <loader v-if="isloading"></loader>
        </div>

        <VCardText>
            <VForm ref="formValid" v-model="formValid">
                <!-- avatar -->
                <VRow
                    v-if="type === 'usuario' || usuario.role == 2 || !usuario.role"
                    dense>
                    <VCol cols="12" align="center">
                        <VAvatar
                            size="120px"
                            style="cursor: pointer;"
                            @click="buscarAvatar"
                        >
                            <img
                                style="border-radius: 50%;"
                                :src="imagePreview[0]"
                                v-if="imagePreview.length > 0"
                                class="img-thumbnails"
                                width="100%"
                            />

                            <img
                                style="border-radius: 50%;"
                                src="@/assets/images/default.png"
                                v-if="
                                    imagePreview.length == 0 &&
                                    this.editMode == false
                                "
                                class="img-thumbnails"
                                width="100%"
                            />

                            <img
                                style="border-radius: 50%;"
                                src="@/assets/images/default.png"
                                v-if="
                                    !usuario.avatar &&
                                    editMode == true &&
                                    imagePreview.length == 0
                                "
                                class="img-thumbnails"
                                width="100%"
                            />

                            <img
                                v-if="
                                    editMode == true &&
                                    usuario.avatar &&
                                    imagePreview.length == 0
                                "
                                style="border-radius: 50%;"
                                :src="
                                    uri +
                                    '/storage/users/userId_' +
                                    usuario.id +
                                    '/' +
                                    usuario.avatar
                                "
                                class="img-thumbnails"
                                width="100%"
                            />
                            <file-input
                                class="inputFile d-none"
                                :files="files"
                                v-on:file-change="setFiles"
                                file-clear="clearFiles"
                                id="inputFile"
                                ref="inputFile"
                            />
                        </VAvatar>
                    </VCol>
                </VRow>

                <template v-if="type === 'usuario'">
                    <VRow dense>
                        <VCol cols="12" md="6">
                            <VTextField
                                density="compact"
                                variant="outlined"
                                :error-messages="
                                    errors.errors.name
                                        ? errors.errors.name[0]
                                        : null
                                "
                                v-model="usuario.name"
                                label="Nombre"
                                placeholder="Nombre"
                                :rules="[rules.required]"
                            />
                        </VCol>
                        <VCol cols="12" md="6">
                            <VTextField
                                density="compact"
                                variant="outlined"
                                :error-messages="
                                    errors.errors.email
                                        ? errors.errors.email[0]
                                        : null
                                "
                                v-model="usuario.email"
                                label="Email"
                                placeholder="Email"
                                :rules="[rules.required, rules.email]"
                            />
                        </VCol>
                    </VRow>
                    <VRow
                        v-if="!editMode"
                        dense
                        class="mt-2">
                        <VCol cols="12" md="6">
                            <VTextField
                                density="compact"
                                variant="outlined"
                                v-model="usuario.password"
                                type="password"
                                autocomplete="new-password"
                                label="Contraseña"
                                :rules="[rules.required, rules.password]"
                            />
                        </VCol>
                        <VCol cols="12" md="6">
                            <VTextField
                                density="compact"
                                variant="outlined"
                                v-model="passwordConfirm"
                                type="password"
                                autocomplete="new-password"
                                label="Confirmar contraseña"
                                :rules="[
                                    rules.required,
                                    (v) =>
                                        confirmedValidator(v, usuario.password),
                                ]"
                            />
                        </VCol>
                    </VRow>
                    <VRow
                        dense
                        :class="editMode ? 'mt-4' : 'mt-2'">
                        <VCol cols="12" align="center">
                            <VBtn
                                v-if="editMode == false"
                                @click="saveUsuario"
                                :disabled="isloading"
                                class="me-3"
                                >Confirmar
                            </VBtn>

                            <VBtn
                                v-if="editMode == true"
                                @click="updateUsuario"
                                :disabled="isloading"
                                class="me-3"
                                >Actualizar</VBtn
                            >

                            <VBtn
                                v-if="editMode && canResetEmployeePassword"
                                @click="openDialogResetPassword"
                                :disabled="isloading"
                                color="info"
                                variant="tonal"
                                class="me-3"
                            >
                                Restablecer contraseña y enviar email
                            </VBtn>

                            <VBtn
                                @click="close"
                                :disabled="isloading"
                                color="secondary"
                                >Cancelar</VBtn
                            >
                        </VCol>
                    </VRow>
                </template>

                <template v-else>
                <VRow>
                    <VCol cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.name
                                    ? errors.errors.name[0]
                                    : null
                            "
                            v-model="usuario.name"
                            label="Nombre"
                            placeholder="Nombre"
                            :rules="[rules.required]"
                        ></VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.nombre_fiscal
                                    ? errors.errors.nombre_fiscal[0]
                                    : null
                            "
                            v-model="usuario.nombre_fiscal"
                            label="Nombre Fiscal"
                            placeholder="Nombre Fiscal"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                        ></VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.cif ? errors.errors.cif[0] : null
                            "
                            v-model="usuario.cif"
                            label="CIF"
                            placeholder="CIF"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                        ></VTextField>
                    </VCol>
                    <!-- Email: solo visible para usuarios (gestores, empleados, admin); en empresa no se muestra -->
                    <VCol v-if="usuario.role != 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.email
                                    ? errors.errors.email[0]
                                    : null
                            "
                            v-model="usuario.email"
                            label="Email"
                            placeholder="Email"
                            :rules="[rules.required, rules.email]"
                        ></VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.email_comercial
                                    ? errors.errors.email_comercial[0]
                                    : null
                            "
                            v-model="usuario.email_comercial"
                            label="Email comercial"
                            placeholder="Email comercial"
                        ></VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.telefono
                                    ? errors.errors.telefono[0]
                                    : null
                            "
                            v-model="usuario.telefono"
                            :rules="
                                usuario.role == 2
                                    ? [rules.required, rules.number_rule]
                                    : []
                            "
                            counter
                            maxlength="9"
                            label="Teléfono"
                            placeholder="Teléfono"
                        >
                        </VTextField>
                    </VCol>
                    <VCol cols="12" md="4">
                        <VSelect
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.role
                                    ? errors.errors.role[0]
                                    : null
                            "
                            :items="rolesFiltered"
                            item-value="id"
                            item-title="role"
                            label="Seleccione un Perfil"
                            v-model="usuario.role"
                            :rules="[rules.required]"
                        >
                        </VSelect>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.direccion
                                    ? errors.errors.direccion[0]
                                    : null
                            "
                            label="Direccion"
                            v-model="usuario.direccion"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                        >
                        </VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.ciudad
                                    ? errors.errors.ciudad[0]
                                    : null
                            "
                            v-model="usuario.ciudad"
                            label="Localidad"
                            :counter="60"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                        ></VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="4">
                        <VTextField
                            variant="outlined"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                            v-model="usuario.postal_code"
                            label="Código postal"
                            required
                        />
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="4" md="4">
                        <VSelect
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.provincia_id
                                    ? errors.errors.provincia_id[0]
                                    : null
                            "
                            :items="provincias"
                            item-value="id"
                            item-title="nombre"
                            label="Provincia"
                            v-model="usuario.provincia_id"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                        >
                        </VSelect>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12" md="8">
                        <VTextField
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                errors.errors.cuenta
                                    ? errors.errors.cuenta[0]
                                    : null
                            "
                            v-model="usuario.cuenta"
                            label="Cuenta Bancaria"
                            :rules="usuario.role == 2 ? [rules.required] : []"
                        ></VTextField>
                    </VCol>
                    <VCol v-if="usuario.role == 2" cols="12">
                        <VCheckbox
                            v-model="usuario.has_electronic_billing"
                            label="¿Tiene facturación electrónica?"
                        />
                    </VCol>
                    <!-- Campo para asociar gestores a clientes -->
                    <VCol v-if="usuario.role == 2" cols="12">
                        <VSelect
                            density="compact"
                            variant="outlined"
                            :items="gestores"
                            item-value="id"
                            item-title="name"
                            label="Gestores Asociados"
                            v-model="usuario.gestores_ids"
                            multiple
                            chips
                            closable-chips
                        >
                        </VSelect>
                    </VCol>
                    <!-- Campo para asociar clientes a gestores -->
                    <VCol v-if="usuario.role == 3" cols="12">
                        <VSelect
                            density="compact"
                            variant="outlined"
                            :items="clientes"
                            item-value="id"
                            item-title="name"
                            label="Empresas Asociados"
                            v-model="usuario.clientes_ids"
                            multiple
                            chips
                            closable-chips
                        >
                        </VSelect>
                    </VCol>
                    <!-- Campo para asociar clientes a empleados -->
                    <VCol v-if="usuario.role == 4" cols="12">
                        <VSelect
                            density="compact"
                            variant="outlined"
                            :items="clientes"
                            item-value="id"
                            item-title="name"
                            label="Empresas Asociados"
                            v-model="usuario.clientes_ids"
                            multiple
                            chips
                            closable-chips
                        >
                        </VSelect>
                    </VCol>
                    <VCol cols="12" align="center">
                        <VBtn
                            v-if="editMode == false"
                            @click="saveUsuario"
                            :disabled="isloading"
                            class="me-3"
                            >Confirmar
                        </VBtn>

                        <VBtn
                            v-if="editMode == true"
                            @click="updateUsuario"
                            :disabled="isloading"
                            class="me-3"
                            >Actualizar</VBtn
                        >

                        <VBtn
                            v-if="editMode && canResetEmployeePassword"
                            @click="openDialogResetPassword"
                            :disabled="isloading"
                            color="info"
                            variant="tonal"
                            class="me-3"
                        >
                            Restablecer contraseña y enviar email
                        </VBtn>

                        <VBtn
                            @click="close"
                            :disabled="isloading"
                            color="secondary"
                            >Cancelar</VBtn
                        >
                    </VCol>
                </VRow>
                </template>
            </VForm>
        </VCardText>
        <VDialog v-model="dialogResetPassword" max-width="450" persistent>
            <VCard title="Restablecer contraseña">
                <VCardText>
                    <p v-if="usuario.name">
                        ¿Enviar nueva contraseña por email a <strong>{{ usuario.name }}</strong> ({{ usuario.email }})?
                    </p>
                    <p class="text-caption text-medium-emphasis mt-2">
                        Se generará una contraseña nueva y se enviará al correo del usuario.
                    </p>
                </VCardText>
                <VCardActions>
                    <VSpacer />
                    <VBtn color="secondary" variant="text" @click="dialogResetPassword = false">Cancelar</VBtn>
                    <VBtn color="primary" :loading="sendingResetPassword" @click="confirmResetEmployeePasswordFromForm">Enviar</VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
    </VCard>
</template>

<script>
import FileInput from "@/components/FileInput.vue";
import { UploadFilesService } from "@/utils/UploadFilesService";
import {
    confirmedValidator,
    emailValidator,
    passwordValidator,
    requiredValidator,
} from "@core/utils/validators";
export default {
    props: ["value", "type"],

    components: {
        "file-input": FileInput,
    },

    data() {
        return {
            rules: {
                required: requiredValidator,
                email: emailValidator,
                password: passwordValidator,
                number_rule: (value) => /^\d+$/.test(value) || "Campo numérico",
            },
            passwordConfirm: "",
            formValid: true,
            editMode: false,
            usuario: {
                id: null,
                provincia_id: null,
                name: "",
                nombre_fiscal: "",
                cif: "",
                telefono: "",
                ciudad: "",
                email: "",
                email_comercial: "",
                password: "",
                role: null,
                direccion: "",
                avatar: "",
                cuenta: "00000000000000000000",
                has_electronic_billing: true,
                gestores_ids: [],
                clientes_ids: [],
            },
            roles: [
                {
                    id: 1,
                    role: "Administrador",
                },
                {
                    id: 2,
                    role: "Cliente",
                },
                {
                    id: 3,
                    role: "Gestor",
                },
                {
                    id: 4,
                    role: "Empleado",
                },
            ],
            provincias: [],
            gestores: [],
            clientes: [],

            files: [],
            imagePreview: [],
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            dialogResetPassword: false,
            sendingResetPassword: false,
        };
    },

    watch: {
        value: {
            immediate: true,
            handler(newVal) {
                if (newVal != null) {
                    this.usuario = { ...newVal };
                    // Asegurarse de que id sea null si no está definido
                    if (
                        this.usuario.id === undefined ||
                        this.usuario.id === "undefined"
                    ) {
                        this.usuario.id = null;
                    }
                    // editMode solo es true si el id existe y es válido
                    this.editMode =
                        this.usuario.id !== null &&
                        this.usuario.id !== undefined &&
                        this.usuario.id !== "" &&
                        this.usuario.id !== 0;
                    this.usuario.has_electronic_billing = Boolean(
                        this.usuario.has_electronic_billing
                    );
                    // Cargar IDs de asociaciones directamente (ya vienen del backend)
                    // Convertir a números para que VSelect los reconozca correctamente
                    if (
                        this.usuario.gestores_ids &&
                        Array.isArray(this.usuario.gestores_ids)
                    ) {
                        this.usuario.gestores_ids =
                            this.usuario.gestores_ids.map((id) => parseInt(id));
                    } else {
                        this.usuario.gestores_ids = [];
                    }

                    if (
                        this.usuario.clientes_ids &&
                        Array.isArray(this.usuario.clientes_ids)
                    ) {
                        this.usuario.clientes_ids =
                            this.usuario.clientes_ids.map((id) => parseInt(id));
                    } else {
                        this.usuario.clientes_ids = [];
                    }

                    if (this.type === "usuario") {
                        this.usuario.role = 1;
                        this.usuario.gestores_ids = [];
                        this.usuario.clientes_ids = [];
                    }
                    this.usuario.password = "";
                    this.passwordConfirm = "";
                } else {
                    this.editMode = false;
                    this.clearForm();
                }
            },
        },
        usuario: {
            handler(newVal) {
                this.$emit("input", newVal);
            },
            deep: true,
        },
        "usuario.role": {
            handler(newRole) {
                if (newRole == 2) {
                    // Si es cliente, cargar gestores
                    this.loadGestores();
                } else if (newRole == 3) {
                    // Si es gestor, cargar clientes
                    this.loadClientes();
                } else if (newRole == 4) {
                    // Si es empleado, cargar clientes
                    this.loadClientes();
                }
            },
        },
        // Watch para cuando cambian los clientes disponibles (para asegurar que se seleccionen)
        clientes: {
            handler() {
                // Si hay clientes_ids pero no están seleccionados, forzar actualización
                if (
                    (this.usuario.role == 3 || this.usuario.role == 4) &&
                    this.usuario.clientes_ids &&
                    this.usuario.clientes_ids.length > 0
                ) {
                    this.$nextTick(() => {
                        // Forzar actualización del v-model
                        const ids = [...this.usuario.clientes_ids];
                        this.usuario.clientes_ids = [];
                        this.$nextTick(() => {
                            this.usuario.clientes_ids = ids;
                        });
                    });
                }
            },
            deep: true,
        },
    },

    created() {
        // this.editMode = false;
        if (this.user != null) {
            this.usuario = this.user;
        }
        /*if (this.$route.query.id) {
        this.editMode = true
        this.getUsuarioById(this.$route.query.id)
      }*/

        // if (this.editMode == false) {
        //datos que se necesitaran en el formulario
        this.getMethodsForm();
        // Cargar listas de gestores y clientes
        this.loadGestores();
        this.loadClientes();
        // }
    },

    methods: {
        buscarAvatar() {
            this.$refs.inputFile.showFilePicker();
        },

        setFiles(files) {
            const filesPreview = files;

            Object.keys(filesPreview).forEach((i) => {
                const file = filesPreview[i];
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview.push(reader.result);
                };
                this.imagePreview = [];
                reader.readAsDataURL(file);
            });

            if (files !== undefined) {
                this.files = files;
                this.disableUploadButtonImage = false;
            }
        },

        getMethodsForm() {
            axios.get(`api/get-methods-form`).then(
                (res) => {
                    this.provincias = res.data.provincias;
                },
                (res) => {
                    $toast.error("Error consultando Usuario");
                }
            );
        },

        loadGestores() {
            axios.get(`api/get-gestores`).then(
                (res) => {
                    this.gestores = res.data.gestores || [];
                },
                (res) => {
                    console.error("Error consultando Gestores");
                }
            );
        },

        loadClientes() {
            axios.get(`api/get-usuarios-clientes`).then(
                (res) => {
                    this.clientes = res.data.clientes || [];
                },
                (res) => {
                    console.error("Error consultando Clientes");
                }
            );
        },

        updateUsuario() {
            // Validar que el id existe y es válido antes de actualizar
            if (
                !this.usuario.id ||
                this.usuario.id === null ||
                this.usuario.id === "null" ||
                this.usuario.id === "undefined"
            ) {
                // Si no hay id válido, usar saveUsuario en su lugar
                this.saveUsuario();
                return;
            }

            if (this.type === "usuario") {
                this.usuario.role = 1;
                this.usuario.gestores_ids = [];
                this.usuario.clientes_ids = [];
            }

            let formDataUpdate = new FormData();

            for (let fileSave of this.files) {
                UploadFilesService.validateUploadedFile(fileSave);
                formDataUpdate.append("imagen[]", fileSave, fileSave.name);
            }
            formDataUpdate.append("usuario", JSON.stringify(this.usuario));

            axios
                .post("api/update-usuario/" + this.usuario.id, formDataUpdate)
                .then(
                    (res) => {
                        $toast.sucs("Usuario actualizado con éxito");
                        this.$emit("close");
                    },
                    (res) => {
                        $toast.error("Error guardando usuario");
                    }
                );
        },

        saveUsuario() {
            this.$refs.formValid.validate();
            if (this.formValid) {
                if (this.type === "usuario") {
                    this.usuario.role = 1;
                    this.usuario.gestores_ids = [];
                    this.usuario.clientes_ids = [];
                }

                let formDataSave = new FormData();

                for (let fileSave of this.files) {
                    UploadFilesService.validateUploadedFile(fileSave);
                    formDataSave.append("imagen[]", fileSave, fileSave.name);
                }

                formDataSave.append("usuario", JSON.stringify(this.usuario));

                axios.post("api/save-usuario", formDataSave).then(
                    (res) => {
                        $toast.sucs("Usuario guardado con éxito");
                        this.$emit("close");
                    },
                    (res) => {
                        $toast.error("Error guardando usuario");
                    }
                );
            }
        },

        getUsuarioById(usuario_id) {
            axios.get(`api/get-usuario-by-id/${usuario_id}`).then(
                (res) => {
                    this.usuario = res.data.user;
                    this.provincias = res.data.provincias;

                    // Los IDs ya vienen directamente del backend
                    // Solo asegurarse de que sean arrays
                    if (!this.usuario.gestores_ids) {
                        this.usuario.gestores_ids = [];
                    }
                    if (!this.usuario.clientes_ids) {
                        this.usuario.clientes_ids = [];
                    }
                    this.usuario.password = "";
                    this.passwordConfirm = "";
                },
                (res) => {
                    $toast.error("Error consultando Usuario");
                }
            );
        },

        close() {
            this.$emit("close");
            this.clearForm();
        },

        clearForm() {
            this.usuario = {
                id: null,
                provincia_id: null,
                name: "",
                nombre_fiscal: "",
                cif: "",
                telefono: "",
                ciudad: "",
                email: "",
                email_comercial: "",
                password: "",
                role: this.type === "usuario" ? 1 : null,
                direccion: "",
                avatar: "",
                cuenta: "00000000000000000000",
                has_electronic_billing: true,
                gestores_ids: [],
                clientes_ids: [],
            };
            this.passwordConfirm = "";
        },

        openDialogResetPassword() {
            this.dialogResetPassword = true;
        },

        confirmResetEmployeePasswordFromForm() {
            this.sendingResetPassword = true;
            axios
                .post(`api/reset-employee-password/${this.usuario.id}`)
                .then((res) => {
                    $toast.sucs(res.data.message || "Contraseña restablecida y email enviado correctamente.");
                    this.dialogResetPassword = false;
                })
                .catch((err) => {
                    const msg = err.response?.data?.error || err.response?.data?.message?.[0] || "Error al resetear la contraseña.";
                    $toast.error(msg);
                })
                .finally(() => {
                    this.sendingResetPassword = false;
                });
        },
    },

    computed: {
        isloading() {
            return this.$store.getters.getloading;
        },

        errors() {
            return this.$store.getters.geterrors;
        },

        uri() {
            return window.location.origin;
        },
        idUser() {
            return localStorage.user_id;
        },

        rolesFiltered() {
            // Si es usuario, filtrar roles excepto cliente
            if (this.type == "usuario") {
                return this.roles.filter((role) => role.id != 2);
            } else {
                // Si es empresa, filtrar solo cliente
                return this.roles.filter((role) => role.id == 2);
            }
        },

        canResetEmployeePassword() {
            // El rol puede venir del store o de localStorage (el login guarda en localStorage)
            const storeUser = this.$store.getters.getuser;
            const currentRole = storeUser?.role != null && storeUser.role !== 0
                ? Number(storeUser.role)
                : Number(localStorage.getItem('role') || 0);
            if (![1, 2, 3, 4].includes(currentRole)) return false;
            const role = Number(this.usuario.role);
            return [1, 2, 3, 4].includes(role);
        },
    },
};
</script>

<style>
.inputFile {
  position: absolute;
  padding: 100%;
  opacity: 0.1;
}

.inputFile[type] {
  cursor: copy;
}
</style>
