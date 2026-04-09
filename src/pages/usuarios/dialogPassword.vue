<template>
  <div>
    <VRow justify="center">
      <VDialog v-model="openDialog" persistent max-width="38%">
        <VCard>

          <VCardTitle class="text-h5 bg-info text-center pa-4">
            Actualizar Contraseña
          </VCardTitle>

          <VCardText>

            <VRow dense class="mt-5">

              <VCol v-if="info" cols="12" class="mb-4">
                <p class="text-danger">Si no recuerda la contraseña o perdió el mail de acceso, contacte con el administrador para la creación de su nueva contraseña</p>
              </VCol>

              <VCol cols="12">
                <VTextField v-model="user.email" outlined label="Email" :error-messages="errors.email ? errors.email : null"></VTextField>
              </VCol>

              <VCol cols="12">
                <VTextField v-model="user.old_password" type="password" outlined label="Contraseña Actual" :error-messages="errors.old_password ? errors.old_password : null"></VTextField>
              </VCol>

              <VCol cols="12">
                <VTextField v-model="user.new_password" type="password" outlined label="Nueva contraseña" :error-messages="errors.confirm_password ? errors.confirm_password : null"></VTextField>
              </VCol>

              <VCol cols="12">
                <VTextField v-model="user.confirm_password" type="password" outlined label="Confirmar contraseña" :error-messages="errors.confirm_password ? errors.confirm_password : null"></VTextField>
              </VCol>

            </VRow>
          </VCardText>


          <VCardActions>
            <VSpacer></VSpacer>
            <VBtn rounded depressed @click="$emit('close_dialog')" color="secondary">cerrar</VBtn>
            <VBtn rounded depressed @click="changePassword" color="success">Cambiar</VBtn>
          </VCardActions>

        </VCard>
      </VDialog>
    </VRow>
  </div>
</template>


<script>
  export default {
    props: ['dialog', 'info'],
    data() {
      return {
        errors: {

        },
        user: {
          email: null,
          old_password: null,
          new_password: null,
          confirm_password: null
        },

        openDialog: false
      }
    },

    watch: {
      dialog(val) {
        this.openDialog = val
      }
    },

    methods: {
      changePassword() {
        this.errors = {}
        axios.post('api/change-password', this.user).then(res => {
          $toast.sucs('Contraseña actualizada')
          this.$emit('close_dialog')
        }, res => {
          if (res.response.status == 401) {
            this.$set(this.errors, res.response.data.tipo, res.response.data.message[0])
            return console.log(res.response.data);
          }
          $toast.error('Error actualizando contraseña')
        })
      }
    }
  }

</script>

