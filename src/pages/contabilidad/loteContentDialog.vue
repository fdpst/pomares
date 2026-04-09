<template>
  <VDialog @click:outside="closeDialog" v-model="dialog" width="600">

    <VCard>
      <VCardTitle class="text-h5 bg-primary pa-4">
        Enviar email
      </VCardTitle>

      <VCardText class="px-3 py-3">

        <loader v-if="isloading"></loader>

        <VRow>
          <VCol cols="12" sm="12" md="12" lg="12" xl="12">
            <small>Ingrese varios destinatarios. Pulse "ENTER" o "TAB" para separarlos.</small>
            <VCombobox
             v-model="form.emails"
              :items="items"
              label="Destinatarios"
              multiple
              chips>
              <template v-slot:selection="{ attrs, item, parent, selected}">
              <VChip
                :key="JSON.stringify(item)"
                v-bind="attrs"
                :input-value="selected"
                
                @click:close="parent.selectItem(item)"
              >
                <VAvatar
                class="accent white--text"
                left
                v-text="item.slice(0, 1).toUpperCase()"
                ></VAvatar>
                {{ item }}
                <VIcon
                small
                @click="parent.selectItem(item)"
                >
                $delete
                </VIcon>
              </VChip>
              </template>
            </VCombobox>
             <VCol cols="12" md="10">
            <VTextarea v-model="form.descripcion" hide-details filled label="Descripción"></VTextarea>
          </VCol>
          </VCol>

          <VCol cols="12" md="10">
            
          </VCol>
        </VRow>
        
      </VCardText>

      <VDivider></VDivider>

      <VCardActions class="pb-5">
        <VSpacer></VSpacer>
        <VBtn 
          color="info" 
          large 
          @click="sendEmail"
        >
          Enviar Correo
        </VBtn>
        <VBtn 
          color="secondary" 
          large 
          @click="closeDialog"
        >
          Cerrar
        </VBtn>
        <VSpacer></VSpacer>
      </VCardActions>

    </VCard>

  </VDialog>

</template>

<script>
  export default {
    props: ['email_dialog', 'isloading', 'modelFactura'],

    data() {
      return {
        items : [],
        form:{
          emails :'',
          descripcion : '',
          tipo_factura : ''
        },
        dialog: false
      }
    },

    watch: {
      email_dialog(val) {
        this.dialog = val
      }
    },

    methods: {
      sendEmail() {

        this.form.tipo_factura = this.modelFactura

        const effectiveUserId = this.getEffectiveUserId();
        axios.post(`api/enviar-lote-facturas/${effectiveUserId}`, {
          form : this.form
        }).then(res => {
          $toast.sucs('Email enviado con exito')
          this.form = {
            emails :'',
            descripcion : '',
            tipo_factura : ''
          }
          this.closeDialog()
        }, res => {
          $toast.error('Fallo envio de email')
        })
      },

      closeDialog() {
        this.$emit('close_dialog')
      },
      getEffectiveUserId() {
        const role = parseInt(localStorage.getItem("role"));
        const selectedCliente = localStorage.getItem("selected_cliente_id");
        if (role === 3 && selectedCliente) {
          return selectedCliente;
        }
        return localStorage.getItem("user_id");
      },
    },
    computed : {
      objetoUser(){
        let user = {
          nombre : localStorage.user_name,
          email : localStorage.user_email,

        }
        return user
      }
    }

  }
</script>
