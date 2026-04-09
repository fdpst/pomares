<template>
    <VDialog @click:outside="closeDialog" v-model="dialog" width="500">
        <VCard>
            <VCardTitle :class="`text-h5 bg-${color} text-center pa-4`">
                Enviar email
            </VCardTitle>
            <VCardText class="px-3 py-3">
                <loader v-if="isloading"></loader>
                <VRow>
                    <VCol
                        cols="12"
                        md="10"
                        offset-md="1"
                        offset-lg="1"
                        offset-xl="1"
                        offset-sm="0"
                    >
                        <v-combobox
                            label="Destinatario(s)"
                            v-model="sendto.email"
                            :items="sendto.addressees"
                            chips
                            multiple
                        />
                        <!-- <VTextField
                            class="mt-4"
                            append-icon="ri-pencil-line"
                            v-model="sendto.email"
                            hide-details
                            :filled="email_disabled"
                            label="Email"
                            :readonly="email_disabled"
                            @click:append="
                                email_disabled = !email_disabled
                            "></VTextField> -->
                    </VCol>
                    <VCol cols="12">
                        <VTextField
                            v-model="sendto.subject"
                            label="Asunto"
                        ></VTextField>
                    </VCol>
                    <VCol cols="12">
                        <div>
                            <RichTextComponent
                                v-model="sendto.body"
                                ref="richTextComponent"
                            >
                            </RichTextComponent>
                        </div>
                    </VCol>
                </VRow>
            </VCardText>
            <VDivider></VDivider>
            <VCardActions>
                <VBtn @click="sendEmail">aceptar</VBtn>
                <VSpacer></VSpacer>
                <VBtn color="secondary" @click="closeDialog">cerrar</VBtn>
            </VCardActions>
        </VCard>
    </VDialog>
</template>

<script>
import { ParamSystemEnum } from "../../../@core/types/ParamSystem.enum";
import RichTextComponent from "../../recibos/RichTextComponent.vue";

export default {
    props: [
        "email_dialog",
        "email",
        "url_files",
        "isloading",
        "tipo",
        "user_id",
        "id_factura",
        "color",
    ],
    mounted() {
        this.getDefaultEmailText();
    },
    components: {
        RichTextComponent,
    },
    data() {
        return {
            email_disabled: true,
            sendto: {
                email: null,
                archivo: {
                    label: null,
                    archivo: null,
                },
                subject: "",
                body: "Recibo",
                from: "Envió documentos",
                addressees: [],
            },
            dialog: false,
            defaultEmailBody: "",
        };
    },

    watch: {
        email: {
            handler: function (n) {
                this.sendto.email = n;
                this.obtenerEmail();
            },
            deep: true,
        },
        email_dialog: {
            handler: function (val) {
                const vm = this;
                vm.sendto.body = null;
                this.dialog = val;

                vm.sendto.body = vm.defaultEmailBody;
            },
            deep: true,
        },
    },
    methods: {
        async getDefaultEmailText() {
            const res = await $api(
                "/api/system-params/by-name/" +
                    ParamSystemEnum.TEXT_EMAIL_PAY_REMINDER,
                {
                    method: "GET",
                }
            );
            this.defaultEmailBody = res.value;
            this.sendto.body = res.value;
        },
        obtenerEmail() {
            if (this.email != null) {
                this.sendto.email = this.email;
                return;
            }
            axios.get(`api/get-email-usuario/${this.user_id}`).then(
                (res) => {
                    this.sendto.from = res.data;
                    this.closeDialog();
                },
                (res) => {
                    this.$toast.error("Fallo obteniendo email de usuario");
                }
            );
        },
        sendEmail() {
            this.sendto.archivo = {
                label: this.tipo,
                archivo: this.name_files[this.tipo],
            };
            this.sendto.id = this.id_factura;
            if (this.id_factura == null) {
                return;
            }
            axios
                .post(`api/send-email`, this.sendto)
                .then((res) => {
                    $toast.sucs("Email enviado con éxito");
                    this.email_disabled = true;
                    this.sendto.archivo = {
                        label: null,
                        archivo: null,
                    };
                })
                .catch((error) => {
                    const res = error.response?.data;
                    console.log("response", res.error);
                    const message =
                        res?.error ??
                        res?.message ??
                        "Error al enviar el email";
                    $toast.error(message);
                });

            this.closeDialog();
        },

        closeDialog() {
            this.dialog = false;
            this.$emit("close_dialog");
        },
    },

    computed: {
        name_files() {
            let items = [];
            items["nota"] = this.url_files.nota_url;
            items["presupuesto"] = this.url_files.presupuesto_url;
            items["factura"] = this.url_files.factura_url;
            return items;
        },
    },
};
</script>
