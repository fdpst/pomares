<template>
  <VDialog v-model="dialog" persistent max-width="500">
    <VCard>
      <VCardTitle class="text-h5 text-center pa-4">
        {{ token_success ?  'Introducir Código' : 'Introducir contraseña' }} 
      </VCardTitle>

      <VCardText class="mt-5">
        <VRow dense>
          <VTextField v-if="!token_success" v-model="user.password" type="password" outlined label="contraseña"></VTextField>
          <VTextField v-if="token_success" v-model="user.codigo" outlined label="código"></VTextField>
        </VRow>

        <VRow class="justify-end">
          <VBtn color="secondary" variant="outlined" @click="$emit('update:modelValue', false)">Cerrar</VBtn>
          <VBtn v-if="!token_success" @click="requestToken" class="mx-2">Aceptar</VBtn>
          <VBtn v-if="token_success" @click="verificarCodigo">Verificar Código</VBtn>
        </VRow>
      </VCardText>

     
    </VCard>
  </VDialog>
</template>
<script>
  export default {

    props: ['openDialogCheckPassword'],

    data() {
      return {
        token_success: false,
        dialog: false,
        user: {
          password: null,
          codigo: null
        }
      }
    },

    watch: {
      openDialogCheckPassword(val) {
        this.dialog = val
      }
    },

    methods: {
      requestToken() {
        axios.post('api/get-password-token', this.user).then(res => {
          $toast.sucs('El código ha sido enviado')
          this.token_success = true
        }, res => {
          $toast.error('Error Obteniendo Codigo')
        })
      },
      verificarCodigo() {
        axios.post('api/verificar-codigo', this.user).then(res => {
          this.token_success = false
          $toast.sucs('Código verificado')
          axios.defaults.headers.common['X-PASSWORD-TOKEN'] = res.data.token
          this.$emit('get_passwords')
          this.$emit('update:modelValue', false)
        }, res => {
          $toast.error('Error Obteniendo Codigo')
        })
      }
    }
  };
</script>

<style>
  .tittlecard {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    margin-bottom: 30px !important;
    background-color: #FDCB25 !important;
  }
</style>
