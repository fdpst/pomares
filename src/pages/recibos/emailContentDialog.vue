<template>
  <VDialog @click:outside="closeDialog" v-model="dialog" max-width="900" class="email-dialog">
    <VCard>
      <VCardTitle
        class="text-h5 text-center pa-4"
      >
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
          <VTextField
            class="mt-4"
            append-icon="ri-pencil-line"
            v-model="sendto.email"
            hide-details
            :filled="email_disabled"
            label="Email"
            :readonly="email_disabled"
            @click:append="email_disabled = !email_disabled"
          ></VTextField>
          </VCol>
          <VCol cols="12">
            <VTextField
              v-model="sendto.subject"
              label="Asunto"
            ></VTextField>
          </VCol>
          <VCol cols="12">
          <div class="richtext-field">
            <label class="richtext-label">Cuerpo</label>
            <RichTextComponent
              v-model="sendto.body"
            />
          </div>
          </VCol>
        </VRow>
      </VCardText>
      <VDivider></VDivider>
      <VCardActions class="justify-center pb-4">
        <VBtn
          rounded="pill"
          color="secondary"
          class="btn-cancel-dialog"
          @click="closeDialog"
        >
          cancelar
        </VBtn>
        <VBtn
          rounded="pill"
          color="#5142A6"
          class="btn-confirm-dialog"
          @click="sendEmail"
        >
          enviar
        </VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>

<script>
import RichTextComponent from '@/pages/recibos/RichTextComponent.vue'
export default {
  props: [
    "email_dialog",
    "email",
    "url_files",
    "isloading",
    "tipo",
    "user_id",
    "id_factura",
    "color"
  ],

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
        body: "<p>Recibo</p>",
        from: "Envió documentos",
      },
      dialog: false
    };
  },

  components: {
    RichTextComponent,
  },

  watch: {
    email(n) {
      this.sendto.email = n;
      this.obtenerEmail();
    },

    email_dialog(val) {
      this.dialog = val
    }
  },

  methods: {
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
      axios.post(`api/send-email`, this.sendto).then(
        (res) => {
          this.$toast.sucs("Email enviado con éxito");
          this.email_disabled = true;
          this.sendto.archivo = {
            label: null,
            archivo: null,
          };
        })
        .catch(error => {
          const res = error.response?.data;
          console.log('response', res.error);
          const message = res?.error ?? res?.message ?? 'Error al enviar el email';
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
