<template>
    <VRow>
        <VCol
            md="10"
            cols="12">
            <VCard title="Guardar Gasto">
                <VDivider></VDivider>

                <VForm class="mt-5">
                    <VContainer>
                        <VRow dense>
                            <VCol
                                cols="12"
                                md="4">
                                <VTextField
                                    class="my-input"
                                    filled
                                    :error-messages="
                                        errors.errors.codigo
                                            ? errors.errors.codigo[0]
                                            : null
                                    "
                                    v-model="gasto.codigo"
                                    label="Código"
                                    required></VTextField>
                            </VCol>

                            <VCol
                                cols="12"
                                md="4">
                                <VTextField
                                    filled
                                    :error-messages="
                                        errors.errors.importe
                                            ? errors.errors.importe[0]
                                            : null
                                    "
                                    v-model="gasto.importe"
                                    @change="validaImporte($event.target.value)"
                                    label="Importe"
                                    required
                                    prefix=""></VTextField>

                                <small v-if="errorImporte">
                                    {{ errorImporte }}
                                </small>
                            </VCol>

                            <VCol
                                cols="12"
                                md="4">
                                <CRUDSelect
                                    v-model="gasto.tipo_id"
                                    title="tipo de gasto"
                                    :items="tipos"
                                    item-title="nombre"
                                    item-value="id"
                                    label="Tipo"
                                    placeholder="Selecciona un tipo de gasto"
                                    clearable
                                    @submit="guardarTipo"
                                    @update:selectedItem="onEditGasto"
                                    @delete="onDeleteGasto">
                                    <template #form>
                                        <VCol cols="12">
                                            <VTextField
                                                v-model="tipo_gasto.nombre"
                                                label="Nombre"
                                                :rules="[requiredValidator]" />
                                        </VCol>
                                    </template>
                                </CRUDSelect>
                            </VCol>
                        </VRow>

                        <VRow>
                            <VCol
                                cols="12"
                                md="6">
                                <AppDateTimePicker
                                    v-model="gasto.fecha"
                                    label="Fecha"
                                    prepend-icon="ri-calendar-fill"
                                    :error-messages="
                                        errors.errors.fecha
                                            ? errors.errors.fecha[0]
                                            : null
                                    " />
                            </VCol>

                            <VCol
                                cols="12"
                                md="6">
                                <VFileInput
                                    filled
                                    prepend-icon="ri-file-image-line"
                                    ref="file"
                                    accept=""
                                    label="Archivo"
                                    v-model="gasto.archivo"></VFileInput>

                                <VCol
                                    v-if="isloading"
                                    cols="12"
                                    md="5">
                                    <VProgressLinear
                                        v-model="uploadPercentage"
                                        height="25">
                                        <strong
                                            >{{
                                                Math.ceil(uploadPercentage)
                                            }}%</strong
                                        >
                                    </VProgressLinear>
                                </VCol>
                            </VCol>
                        </VRow>

                        <VRow
                            class="mb-3"
                            dense>
                            <a
                                v-if="
                                    gasto.id &&
                                    gasto.nombre_archivo != null &&
                                    gasto.nombre_archivo != false
                                "
                                target="_blank"
                                :href="gasto.path">
                                {{ gasto.nombre_archivo }}
                            </a>
                        </VRow>

                        <VRow dense> </VRow>

                        <VRow dense>
                            <VCol cols="12">
                                <VTextarea
                                    v-model="gasto.descripcion"
                                    label="Descripción"></VTextarea>
                            </VCol>
                        </VRow>
                    </VContainer>

                    <VDivider class="mt-5"></VDivider>

                    <VContainer>
                        <VRow>
                            <VCol
                                cols="12"
                                md="3">
                                <VBtn
                                    rounded
                                    depressed
                                    @click="saveGasto"
                                    :disabled="isloading"
                                    color="primary"
                                    class="white--text me-2"
                                    >Guardar</VBtn
                                >

                                <RouterLink
                                    :to="`/lista-gastos`"
                                    class="action-buttons">
                                    <VBtn
                                        rounded
                                        depressed
                                        color="secondary"
                                        class="white--text"
                                        >Volver</VBtn
                                    >
                                </RouterLink>
                            </VCol>
                        </VRow>
                    </VContainer>
                </VForm>
            </VCard>
        </VCol>
    </VRow>
</template>

<script>
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import CRUDSelect from "../../../components/CRUDSelect.vue";
import Loading from "./Loading.vue";
import {UploadFilesService} from "../../../utils/UploadFilesService";

export default {
    mixins: [gestorClienteMixin],
    components: {
        loading: Loading,
        CRUDSelect,
    },
    data() {
        return {
            loader: null,
            loading3: false,
            gasto_existe: false,
            files: [],
            imagePreview: null,
            levelSelect: null,
            mandar: "",
            clearFileButton: false,
            ocultarImagen: false,
            botonEnviar: false,
            responseScan: "",
            menu: false,
            tipos: [],
            tipo_gasto: {
                id: null,
                nombre: "",
            },
            menu: false,
            uploadPercentage: 0,
            gasto: {
                id: null,
                codigo: "",
                fecha: new Date().toISOString().substr(0, 10),
                importe: "",
                descripcion: "",
                archivo: null,
                nombre_archivo: null,
                path: null,
                tipo_id: null,
                user_id: null,
            },
            errorImporte: "",
            fileName: "",
        };
    },
    watch: {
        loader() {
            const l = this.loader;
            this[l] = !this[l];

            setTimeout(() => (this[l] = false), 3000);

            this.loader = null;
        },

        "gasto.archivo": function (val) {
            this.fileName = val.name.length > 0 ? val.name : "";
        },
    },
    created() {
        this.gasto.user_id = this.effectiveUserId;
        if (this.$route.query.id) {
            this.getGastoById(this.$route.query.id);
        }
        this.getTiposGasto();
    },

    methods: {
        // Gasto
        getGastoById(gasto_id) {
            axios.get(`api/get-gasto-by-id/${gasto_id}`).then(
                (res) => {
                    const data = res.data?.gasto ?? res.data;
                    this.gasto = {
                        ...data,
                        user_id: this.effectiveUserId,
                    };
                },
                (res) => {
                    $toast.error("Error consultando Gasto");
                }
            );
        },
        saveGasto() {
            this.gasto.user_id = this.effectiveUserId;
            let formData = new FormData();

            formData.append("id", this.gasto.id);
            formData.append("codigo", this.gasto.codigo);
            formData.append("importe", this.gasto.importe);
            formData.append("descripcion", this.gasto.descripcion);
            // Archivo opcional: solo validar y enviar si hay archivo
            const archivo = this.gasto.archivo;
            const hasFile = archivo != null && (Array.isArray(archivo) ? archivo.length > 0 : archivo instanceof File);
            if (hasFile) {
                const file = Array.isArray(archivo) ? archivo[0] : archivo;
                UploadFilesService.validateUploadedFile(file);
                formData.append("imagen[]", file);
            }
            formData.append("nombreArchivo", this.fileName);
            formData.append("tipo_id", this.gasto.tipo_id);
            formData.append("fecha", this.gasto.fecha);
            formData.append("user_id", this.gasto.user_id);

            axios
                .post("api/save-gasto", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    onUploadProgress: function (progressEvent) {
                        this.uploadPercentage = parseInt(
                            Math.round(
                                (progressEvent.loaded / progressEvent.total) *
                                    100
                            )
                        );
                    }.bind(this),
                })
                .then(
                    (res) => {
                        $toast.sucs("Gasto guardado con exito");
                        this.$router.push("/lista-gastos");
                    },
                    (res) => {
                        $toast.error("Error guardando gasto");
                    }
                );
        },

        // Tipo de gasto
        getTiposGasto() {
            axios
                .get(`api/get-tipos-gasto`)
                .then(
                    (res) => {
                        this.tipos = res.data;
                    },
                    (res) => {
                        $toast.error("Error consultando tipos de gasto");
                    }
                );
        },
        guardarTipo() {
            const payload = {
                ...this.tipo_gasto,
                user_id: this.effectiveUserId,
            };
            axios.post(`api/save-tipos-gasto`, payload).then(
                (res) => {
                    this.getTiposGasto();
                    // this.tipos.unshift(res.data);
                    // this.gasto.tipo = res.data;
                    // this.gasto_existe = false;
                },
                (res) => {
                    $toast.error("Error consultando Gasto");
                }
            );
        },
        onEditGasto(item) {
            this.tipo_gasto = item;
        },
        onDeleteGasto(id) {
            axios.get(`api/delete-tipos-gasto/${id}`).then(
                (res) => {
                    this.tipos = this.tipos.filter((item) => item.id !== id);
                },
                (res) => {
                    $toast.error("Error eliminando tipo de gasto");
                }
            );
        },
        onClienteChanged(event) {
            console.log('FormGasto: Cliente cambiado, actualizando datos...', event.detail);
            this.gasto.user_id = this.effectiveUserId;
            this.getTiposGasto();
        },

        // Metodos auxiliares
        verDocumento() {
            console.log("ver documento");
        },
        resetInput() {
            // this.ocultarImagen = true
            this.functioResetInput();
            // this.form.nombreImagen = 'Selecciona un logo para tu tienda'
        },
        functioResetInput() {
            var input = document.getElementById("inputFile");
            input.children[0].type = "text";
            input.children[0].type = "file";
            this.files = [];

            // const input = this.$refs.fileInput
            // input.type = 'text'
            // input.type = 'file'
            this.imagePreview = [];
            this.responseScan = "";
            // this.clearFileButton = false
        },
        uploadFile() {
            if (this.files.length == 0) {
                alert("Debe seleccionar al menos un archivo");
                return;
            }

            let formSendFiles = new FormData();
            for (let fileSave of this.files) {
                UploadFilesService.validateUploadedFile(fileSave);
                formSendFiles.append("imagen[]", fileSave, fileSave.name);
            }
            formSendFiles.append("user_id", this.captureUriId);
            formSendFiles.append("carpeta", "ocr");
            formSendFiles.append("parentPholder", "ocr");

            for (var file in this.files.length) {
                formSendFiles.append("imagen[]", file, file.name);
            }

            axios.post(`api/ocr`, formSendFiles).then(
                (res) => {
                    let respuestOk = res.status;
                    if (respuestOk * 1 == 200) {
                        $toast.sucs("Documento cargado");

                        this.responseScan = res.data.ocr;
                        console.log(jsons);
                    }
                },
                (err) => {
                    $toast.error("Error cargando documento(s)");
                }
            );
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
            this.botonEnviar = true;
            console.log(this.imagePreview);

            if (files !== undefined) {
                this.files = files;
                this.disableUploadButtonImage = false;
            }
        },
        limpiar() {
            var input = document.getElementById("inputFile");
            input.children[0].type = "text";
            input.children[0].type = "file";
            this.files = [];
            this.responseScan = "";
        },
        handleSearch(search) {
            if (!search) {
                return null;
            }
            this.gasto_existe =
                this.tipos.filter((value) => value.nombre.startsWith(search))
                    .length === 0
                    ? true
                    : false;
            if (this.gasto_existe) {
                this.gasto.tipo = search;
            }
        },
        validaImporte(valor) {
            this.errorImporte = "";

            var RE = /^\d*\.?\d*$/;
            if (RE.test(valor)) {
                this.errorImporte = "";
            } else {
                this.errorImporte = "Inserte un numero entero o decimal";
                this.gasto.importe = "";
            }

            if (this.gasto.importe.length > 10) {
                // alert('El numero es mu grande')
                this.errorImporte = "Inserte un valor de importe válido";
            }
        },
    },
    computed: {
        captureUriId() {
            return this.effectiveUserId;
        },
        isloading() {
            return this.$store.getters.getloading;
        },

        errors() {
            return this.$store.getters.geterrors;
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

<style media="screen">
.my-input input {
    text-transform: uppercase;
}
</style>
