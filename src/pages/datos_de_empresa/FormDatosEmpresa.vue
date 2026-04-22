<template>
    <VCard title="Editar Datos de Empresa ">
        <VCardText>
            <VRow>
                <VCol
                    cols="12"
                    align="center">
                    <VBtn
                        rounded="pill"
                        color="#5142A6"
                        class="mb-3 mr-3 text-white"
                        @click="dialog = true">
                        cambiar contraseña
                    </VBtn>

                    <VBtn
                        rounded
                        depressed
                        @click="updateUsuario"
                        :disabled="isloading"
                        color="primary"
                        class="white--text mb-3">
                        Actualizar
                    </VBtn>
                </VCol>
            </VRow>
        </VCardText>

        <VForm class="mt-3">
            <VCardText>
                <div class="mt-2">
                    <loader v-if="isloading"></loader>

                    <VRow
                        dense
                        class="mb-3">
                        <VCol
                            cols="12"
                            align="center">
                            <VAvatar
                                size="120px"
                                style="cursor: pointer"
                                @click="buscarAvatar">
                                <img
                                    v-if="
                                        usuario.avatar != null &&
                                        imagePreview.length == 0
                                    "
                                    :src="usuario.avatar"
                                    style="border-radius: 50%"
                                    class="img-thumbnails w-100" />

                                <img
                                    v-else-if="
                                        usuario.avatar == null &&
                                        imagePreview.length == 0
                                    "
                                    src="@/assets/images/default.png"
                                    style="border-radius: 50%"
                                    class="img-thumbnails w-100" />

                                <img
                                    v-else-if="imagePreview.length > 0"
                                    :src="imagePreview[0]"
                                    style="border-radius: 50%"
                                    class="img-thumbnails w-100" />

                                <file-input
                                    class="inputFile d-none"
                                    :files="files"
                                    v-on:file-change="setFiles"
                                    file-clear="clearFiles"
                                    id="inputFile"
                                    ref="inputFile" />
                            </VAvatar>
                        </VCol>
                    </VRow>

                    <VRow dense>
                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.name
                                        ? errors.errors.name[0]
                                        : null
                                "
                                v-model="usuario.name"
                                label="Nombre"
                                required />
                        </VCol>

                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.nombre_fiscal
                                        ? errors.errors.nombre_fiscal[0]
                                        : null
                                "
                                v-model="usuario.nombre_fiscal"
                                label="Nombre Fiscal"
                                required />
                        </VCol>

                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.cif
                                        ? errors.errors.cif[0]
                                        : null
                                "
                                v-model="usuario.cif"
                                label="CIF"
                                required />
                        </VCol>
                    </VRow>

                    <VRow dense>
                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.direccion
                                        ? errors.errors.direccion[0]
                                        : null
                                "
                                label="Direccion"
                                v-model="usuario.direccion" />
                        </VCol>

                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.ciudad
                                        ? errors.errors.ciudad[0]
                                        : null
                                "
                                v-model="usuario.ciudad"
                                label="Localidad"
                                :counter="60"
                                required />
                        </VCol>
                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.postal_code
                                        ? errors.errors.postal_code[0]
                                        : null
                                "
                                v-model="usuario.postal_code"
                                label="Código postal"
                                required />
                        </VCol>
                    </VRow>

                    <VRow dense>
                        <VCol
                            cols="12"
                            md="4">
                            <VSelect
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
                                v-model="usuario.provincia_id" />
                        </VCol>

                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.email_comercial
                                        ? errors.errors.email_comercial[0]
                                        : null
                                "
                                v-model="usuario.email_comercial"
                                label="Email comercial" />
                        </VCol>

                        <VCol
                            cols="12"
                            md="4">
                            <VTextField
                                variant="outlined"
                                :error-messages="
                                    errors.errors.telefono
                                        ? errors.errors.telefono[0]
                                        : null
                                "
                                v-model="usuario.telefono"
                                :rules="[rules.number_rule]"
                                counter
                                maxlength="9"
                                label="Teléfono"
                                required />
                        </VCol>
                    </VRow>
                </div>
            </VCardText>

            <VDivider />

            <VCardText>
                <VRow
                    class="mt-4"
                    dense>
                    <VCol
                        cols="12"
                        md="6">
                        <VTextField
                            persistent-hint
                            counter
                            maxlength="40"
                            dense
                            v-model="usuario.metodos_pago.pago_uno"
                            outlined
                            label="Transferencia bancaria" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            v-model="usuario.metodos_pago.pago_uno_activo"
                            :label="
                                usuario.metodos_pago.pago_uno_activo
                                    ? 'Activo'
                                    : 'Inactivo'
                            "
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            @click.native.stop
                            @change="setPredeterminado"
                            v-model="predeterminado"
                            value="pago_uno"
                            label="Predeterminado"
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>
                </VRow>

                <VRow dense>
                    <VCol
                        cols="12"
                        md="6">
                        <VTextField
                            dense
                            hide-details
                            v-model="usuario.metodos_pago.pago_dos"
                            outlined
                            label="Giro bancario" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            v-model="usuario.metodos_pago.pago_dos_activo"
                            :label="
                                usuario.metodos_pago.pago_dos_activo
                                    ? 'Activo'
                                    : 'Inactivo'
                            "
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            @click.native.stop
                            @change="setPredeterminado"
                            v-model="predeterminado"
                            value="pago_dos"
                            label="Predeterminado"
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>
                </VRow>

                <VRow dense>
                    <VCol
                        cols="12"
                        md="6">
                        <VTextField
                            dense
                            hide-details
                            v-model="usuario.metodos_pago.pago_tres"
                            outlined
                            label="Efectivo €" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            v-model="usuario.metodos_pago.pago_tres_activo"
                            :label="
                                usuario.metodos_pago.pago_tres_activo
                                    ? 'Activo'
                                    : 'Inactivo'
                            "
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            @click.native.stop
                            @change="setPredeterminado"
                            v-model="predeterminado"
                            value="pago_tres"
                            label="Predeterminado"
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>
                </VRow>

                <VRow dense>
                    <VCol
                        cols="12"
                        md="6">
                        <VTextField
                            dense
                            hide-details
                            v-model="usuario.metodos_pago.pago_cuatro"
                            outlined
                            label="Paypal" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            v-model="usuario.metodos_pago.pago_cuatro_activo"
                            :label="
                                usuario.metodos_pago.pago_cuatro_activo
                                    ? 'Activo'
                                    : 'Inactivo'
                            "
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            @click.native.stop
                            @change="setPredeterminado"
                            v-model="predeterminado"
                            value="pago_cuatro"
                            label="Predeterminado"
                            color="#5142A6"
                            class="mt-2" />
                    </VCol>
                </VRow>

                <VRow dense>
                    <VCol
                        cols="12"
                        md="6">
                        <VTextField
                            dense
                            hide-details
                            v-model="usuario.metodos_pago.pago_cinco"
                            outlined
                            label="Bizum" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            v-model="usuario.metodos_pago.pago_cinco_activo"
                            :label="
                                usuario.metodos_pago.pago_cinco_activo
                                    ? 'Activo'
                                    : 'Inactivo'
                            "
                            color="warning accent-2"
                            class="mt-2" />
                    </VCol>

                    <VCol
                        cols="12"
                        md="3">
                        <VSwitch
                            :inset="false"
                            @click.native.stop
                            @change="setPredeterminado"
                            v-model="predeterminado"
                            value="pago_cinco"
                            label="Predeterminado"
                            color="warning accent-2"
                            class="mt-2" />
                    </VCol>
                </VRow>
            </VCardText>
        </VForm>
    </VCard>

    <dialog-password
        :info="false"
        v-on:close_dialog="dialog = false"
        :dialog="dialog"></dialog-password>
</template>

<script>
import FileInput from "@/components/FileInput.vue";
import { UploadFilesService } from "@/utils/UploadFilesService";
import dialogPassword from "./../usuarios/dialogPassword.vue";

export default {
    components: {
        dialogPassword,
        "file-input": FileInput,
    },

    data() {
        return {
            dialog: false,
            editMode: false,
            usuario: {
                id: null,
                provincia_id: "",
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
                postal_code: "",
                metodos_pago: {
                    id: null,
                    pago_uno: null,
                    pago_uno_activo: false,
                    pago_dos: null,
                    pago_dos_activo: false,
                    pago_tres: null,
                    pago_tres_activo: false,
                    pago_cuatro: null,
                    pago_cuatro_activo: false,
                    pago_cinto: null,
                    pago_cinto_activo: false,
                    predeterminado: null,
                },
            },
            predeterminado: [],
            rules: {
                number_rule: (value) => /^\d+$/.test(value) || "Campo numérico",
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
            ],
            provincias: [],
            files: [],
            imagePreview: [],
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            existeDatosEmpres: true,
        };
    },
    mounted() {
        console.log(this.usuario);
    },
    created() {
        this.loadUsuarioData();
        
        // Escuchar cambios en el cliente seleccionado
        window.addEventListener('cliente-selected-changed', this.handleClienteChanged);
        
        // También escuchar cambios en localStorage (para otros casos)
        window.addEventListener('storage', this.handleStorageChange);
    },
    
    beforeUnmount() {
        // Limpiar listeners
        window.removeEventListener('cliente-selected-changed', this.handleClienteChanged);
        window.removeEventListener('storage', this.handleStorageChange);
    },

    methods: {
        buscarAvatar() {
            this.$refs.inputFile.showFilePicker();
        },

        setPredeterminado(n) {
            this.predeterminado = n.slice(-1);
            let metodo_pago = this.predeterminado[0];
            this.usuario.metodos_pago.predeterminado = metodo_pago;
            this.usuario.metodos_pago[`${metodo_pago}_activo`] = true;
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

        updateUsuario() {
            const formDataUpdate = new FormData();
            for (const fileSave of this.files) {
                UploadFilesService.validateUploadedFile(fileSave);
                formDataUpdate.append("imagen[]", fileSave);
            }
            // Misma señal que FormUsuarios (usuarioJsonForApi): sin esto, role 1 + pivote empresa
            // hacía que el backend interpretara "no empresa" y borrara nombre_fiscal, cif, etc.
            formDataUpdate.append(
                "usuario",
                JSON.stringify({ ...this.usuario, es_empresa_form: true })
            );
            formDataUpdate.append("existeDatosEmpres", true);

            // No pasar el id en la URL, el backend lo determinará automáticamente
            axios
                .post("api/update-usuario", formDataUpdate, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(
                    (res) => {
                        $toast.sucs("Usuario actualizado con éxito");

                        // this.$router.push('/lista-usuario')
                    },
                    (res) => {
                        $toast.error("Error guardando usuario");
                    }
                );
        },

        loadUsuarioData() {
            // No necesitamos pasar el user_id, el backend lo determinará automáticamente
            // basándose en el rol y el cliente seleccionado (a través del header X-Selected-Cliente-Id)
            this.getUsuarioById();
        },
        
        handleClienteChanged(event) {
            // Cuando se cambia el cliente seleccionado, recargar los datos
            console.log('Cliente cambiado, recargando datos...', event.detail);
            this.loadUsuarioData();
        },
        
        handleStorageChange(event) {
            // Si cambia selected_cliente_id en localStorage, recargar datos
            // Nota: Este evento solo se dispara cuando el cambio viene de otra pestaña/ventana
            // Para cambios en la misma pestaña, usamos el evento personalizado
            if (event.key === 'selected_cliente_id') {
                const role = parseInt(localStorage.getItem('role'));
                if (role === 3) {
                    console.log('selected_cliente_id cambió en localStorage (otra pestaña), recargando datos...');
                    this.loadUsuarioData();
                }
            }
        },

        getUsuarioById(usuario_id) {
            // No pasar el usuario_id en la URL, el backend lo determinará automáticamente
            // usando el helper basado en el rol y el cliente seleccionado
            axios.get(`api/get-usuario-by-id`).then(
                (res) => {
                    console.log(res.data, " usuario encontrado");
                    this.usuario = res.data.user;
                    let predeterminado =
                        res.data.user.metodos_pago.predeterminado;
                    this.predeterminado = [];
                    predeterminado
                        ? this.predeterminado.push(predeterminado)
                        : null;
                    this.provincias = res.data.provincias;
                },
                (res) => {
                    $toast.error("Error consultando Perfil");
                }
            );
        },
    },

    computed: {
        isloading: function () {
            return this.$store.getters.getloading;
        },
        errors() {
            return this.$store.getters.geterrors;
        },
        uri() {
            return window.location.origin;
        },
    },
};
</script>
