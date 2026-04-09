<template>

  <VDialog 
    v-model="dialog" 
    max-width="500"
    persistent
  >
    <VCard>

      <VCardTitle class="confirm-dialog__title text-h5 text-center pa-4">
        {{title}}
      </VCardTitle>

      <VCardText class="text-center pt-6">
        <h5 class="text-h5">{{text}}</h5>
      </VCardText>

      <VCardActions class="pb-5">
        <VSpacer></VSpacer>
        <VBtn 
          color="secondary" 
          large 
          class="btn-cancel-dialog"
          @click="$emit('cancel')"
        >
          Cancelar
        </VBtn>
        <VBtn 
          :color="color" 
          large 
          class="btn-confirm-dialog"
          @click="$emit('confirm')"
        >
          Confirmar
        </VBtn>
        <VSpacer></VSpacer>
      </VCardActions>

    </VCard>
  </VDialog>
</template>


<script>

  export default {

    props: {
      modelValue: {
        type: Boolean,
        required: true
      },
      title: {
        type: String,
        default: 'Aviso'
      },
      text: {
        type: String,
        default: '¿Estás seguro(a) que deseas continuar?'
      },
      color: {
        type: String,
        default: 'warning'
      }
    },

    data() {
      return {
        dialog: false
      }
    },

    watch: {
      modelValue(val) {
        this.dialog = val
      }
    },

    methods: {

    }

  }

</script>
