<template>
    <VCard title="Modificar Albaran Recibido">
        <VDivider></VDivider>

        <loader v-if="isloading"></loader>

        <VForm class="mt-5">
            <VContainer>
                <VRow dense>
                    <VCol
                        cols="12"
                        md="6">
                        <AppDateTimePicker
                            v-model="albaran.fecha"
                            first-day-of-week="1"
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
                        <VSelect
                            filled
                            v-model="albaran.proveedor_id"
                            :error-messages="
                                errors.errors.proveedor_id
                                    ? errors.errors.proveedor_id[0]
                                    : null
                            "
                            :items="proveedores"
                            item-title="nombre"
                            item-value="id"
                            label="Proveedor"></VSelect>
                    </VCol>
                </VRow>

                <VRow>
                    <VCol
                        cols="12"
                        md="6">
                        <VFileInput
                            filled
                            prepend-icon="ri-file-image-line"
                            ref="file"
                            multiple
                            label="Imagen"
                            v-model="albaran.imagen"></VFileInput>

                        <div
                            class="pa-3"
                            v-if="albaran.id != null">
                            <label> Imagenes </label>

                            <div>
                                <a
                                    v-for="(n, m) in JSON.parse(
                                        this.albaran.pdf
                                    )"
                                    :key="m"
                                    target="_blank"
                                    :href="'/storage/albaranes/recibidos/' + n">
                                    <VIcon
                                        medium
                                        color="primary"
                                        class="mr-2">
                                        ri-file-line
                                    </VIcon>
                                </a>
                            </div>
                        </div>
                    </VCol>

                    <VCol
                        cols="12"
                        md="6">
                        <VTextField
                            filled
                            v-model="albaran.descripcion"
                            label="Descripción"></VTextField>
                    </VCol>
                </VRow>
            </VContainer>

            <VDivider></VDivider>

            <VContainer>
                <VRow>
                    <VCol cols="12">
                        <VBtn
                            rounded
                            depressed
                            @click="updateAlbaran"
                            :disabled="isloading"
                            color="primary"
                            class="white--text"
                            >Guardar</VBtn
                        >
                    </VCol>
                </VRow>
            </VContainer>
        </VForm>
    </VCard>
</template>

<script>
import FileInput from "@/components/FileInput.vue";
import {UploadFilesService} from "../../utils/UploadFilesService";
export default {
    components: {
        "file-input": FileInput,
    },
    data() {
        return {
            menu: false,
            uploadPercentage: 0,
            proveedores: [],
            albaran: {
                id: null,
                fecha: new Date().toISOString().substr(0, 10),
                descripcion: "",
                proveedor_id: "",
                imagen: false,
                path: "",
                imagen_name: "",
                pdf: "",
                nomrePdf: "",
                existePdf: true,
                user_id: localStorage.getItem("user_id"),
            },

            files: [],
            imagePreview: [],
        };
    },

    created() {
        let u = window.location.href;
        let splithash = u.split("/");

        this.getAlbaranById(splithash[splithash.length - 1]);
        this.getProveedores();
    },

    methods: {
        getProveedores() {
            axios.get(`api/get-proveedores/` + this.albaran.user_id).then(
                (res) => {
                    this.proveedores = res.data;
                },
                (res) => {
                    $toast.error("Error consultando Proveedores");
                }
            );
        },

        getAlbaranById(albaran_id) {
            axios.get(`api/get-albaran-by-id/${albaran_id}`).then(
                (res) => {
                    this.albaran = res.data.albaran;
                    this.proveedores = res.data.proveedores;
                },
                (res) => {
                    $toast.error("Error consultando albaran");
                }
            );
        },

        updateAlbaran() {
            let formData = new FormData();

            formData.append("id", this.albaran.id);
            formData.append("user_id", this.userID);
            formData.append("fecha", this.albaran.fecha);
            formData.append("descripcion", this.albaran.descripcion);
            formData.append("proveedor_id", this.albaran.proveedor_id);
            UploadFilesService.validateUploadedFile(this.albaran.imagen);
            formData.append("imagen", this.albaran.imagen);
            formData.append("pdf", this.albaran.pdf);

            if (this.albaran.imagen == null) {
            } else {
                for (let fileSave of this.albaran.imagen) {
                    UploadFilesService.validateUploadedFile(fileSave);
                    formData.append("imagen[]", fileSave, fileSave.name);
                }
            }
            axios
                .post("api/update-albaran/" + this.albaran.id, formData, {
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
                        $toast.sucs("Albaran guardado con exito");
                        this.$router.push({
                            name: 'lista-albaranes-recibidos'
                        });
                    },
                    (res) => {
                        $toast.error("Error guardando albaran");
                    }
                );
        },

        handleFileUploadPdf(pdf) {
            this.files = pdf;
            this.albaran.pdf = pdf;
            this.nomrePdf = pdf.name;
        },
    },
    computed: {
        isloading() {
            return this.$store.getters.getloading;
        },

        errors() {
            return this.$store.getters.geterrors;
        },
        userID() {
            return localStorage.user_id;
        },
    },
};
</script>
