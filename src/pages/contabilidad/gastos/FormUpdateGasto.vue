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
                                    label="Codigo"
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
                                    @change="validaImporte"
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
                                <VSelect
                                    filled
                                    :items="tipos"
                                    item-value="nombre"
                                    item-title="nombre"
                                    v-model="gasto.tipo"
                                    label="Tipo"></VSelect>
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
                                    md="6">
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
                            <a>
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
                                md="6">
                                <VBtn
                                    rounded
                                    depressed
                                    @click="updateGasto"
                                    :disabled="isloading"
                                    color="primary"
                                    class="white--text me-2"
                                    >Actualizar</VBtn
                                >

                                <RouterLink
                                    :to="{path: `/lista-gastos`}"
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
import {UploadFilesService} from "../../../utils/UploadFilesService";
export default {
    data() {
        return {
            menu: false,
            tipos: [],
            menu: false,
            uploadPercentage: 0,
            gasto: {
                id: "",
                codigo: "",
                fecha: new Date().toISOString().substr(0, 10),
                importe: "",
                descripcion: "",
                archivo: null,
                nombre_archivo: null,
                path: null,
                tipo: "",
                user_id: localStorage.getItem("user_id"),
            },

            errorImporte: "",
            fileName: "",
        };
    },

    created() {
        this.getGastoById();
        this.getTiposGasto();
    },

    "gasto.archivo": function (val) {
        this.fileName = val.name.length > 0 ? val.name : "";
    },

    methods: {
        getTiposGasto() {
            axios
                .get(`api/get-tipos-gasto/${localStorage.getItem("user_id")}`)
                .then(
                    (res) => {
                        this.tipos = res.data;
                    },
                    (res) => {
                        $toast.error("Error consultando tipos de gasto");
                    }
                );
        },
        getGastoById() {
            axios.get(`api/get-gasto-by-id/` + this.captureGstoId).then(
                (res) => {
                    this.gasto = res.data.gasto;
                },
                (res) => {
                    $toast.error("Error consultando Gasto");
                }
            );
        },

        updateGasto() {
            let formData = new FormData();

            formData.append("id", this.gasto.id);
            formData.append("codigo", this.gasto.codigo);
            formData.append("importe", this.gasto.importe);
            formData.append("descripcion", this.gasto.descripcion);
            UploadFilesService.validateUploadedFile(this.gasto.archivo);
            formData.append("imagen[]", this.gasto.archivo);
            formData.append("nombreArchivo", this.fileName);
            formData.append("tipo", this.gasto.tipo);
            formData.append("fecha", this.gasto.fecha);
            formData.append("user_id", this.gasto.user_id);

            axios
                .put(`api/update-gasto/`, formData, {
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
                        this.gasto = res.data.gasto;
                        console.log(res.data.gasto);
                        $toast.sucs("Gasto actualizado");
                        this.$router.push("/lista-gastos");
                    },
                    (res) => {
                        $toast.error("Error actualizado  Gasto");
                    }
                );
        },

        validaImporte(valor) {
            this.errorImporte = "";
            //// garces// console.log (valor)
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
        isloading() {
            return this.$store.getters.getloading;
        },

        errors() {
            return this.$store.getters.geterrors;
        },
        captureGstoId() {
            let gastoId = window.location.href;

            let splitGastoid = gastoId.split("/");
            return splitGastoid[splitGastoid.length - 1];
        },
    },
};
</script>

<style media="screen">
.my-input input {
    text-transform: uppercase;
}
</style>
