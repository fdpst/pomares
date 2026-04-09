<template>

  <VDialog v-model="dialog" persistent max-width="500px">
    <VCard>
      <VCardTitle class="text-h5 bg-info text-center pa-4">
        Guardar / Editar Cuenta
      </VCardTitle>

      <VCardText class="mt-5">
        <VRow dense>
          <VCol cols="12">
            <VTextField v-model="password.cuenta" outlined label="Cuenta / Email" :error-messages="errors.cuenta ? errors.cuenta : null"></VTextField>
          </VCol>
          <VCol cols="12">
            <VTextField v-model="password.real_password" type="text" outlined label="Contraseña" :error-messages="errors.real_password ? errors.real_password : null"></VTextField>
          </VCol>
          <VCol cols="12">
            <VTextField v-model="password.detalle" type="text" outlined label="Detalle"></VTextField>
          </VCol>
        </VRow>
      </VCardText>


      <VCardActions>
        <VSpacer></VSpacer>
        <VBtn rounded depressed @click="closeDialog" class="white--text" color="secondary">cerrar</VBtn>
        <VBtn rounded depressed @click="savePassword" class="white--text" color="success">Guardar</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

</template>
<script>
  export default {

    props: ['modelValue', 'passwordForm'],

    data() {
      return {
        dialog: false,
        password: {
          id: null,
          cuenta: null,
          password: null,
          real_password: null,
          detalle: null,
          observaciones: null
        }
      }
    },

    watch: {
      modelValue(val) {
        this.dialog = val
      },

      passwordForm(val) {
        this.password = val
      }
    },

    methods: {
      savePassword() {
        axios.post('api/save-password', this.password).then(res => {
          this.$emit('saved', res.data)
          this.closeDialog()
        }, res => {
          if (res.response.status == 401) {
            return $toast.warn('Token inválido')
          }
          $toast.error('Error guardando cuenta')
        })
      },
      closeDialog() {
        this.clearForm()
        this.$emit('update:modelValue', false)
      },
      clearForm() {
        this.password = {
          id: null,
          cuenta: null,
          password: null,
          real_password: null,
          detalle: null,
          observaciones: null
        }
      }
    },

    computed: {
      errors() {
        return this.$store.getters.geterrors
      }
    }
  }
</script>

<style>
  .tittlecard {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    margin-bottom: 30px !important;
    background-color: #FDCB25 !important;
  }
</style>