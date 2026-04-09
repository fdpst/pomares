<template>

  <VRow justify="center">
    <VDialog v-model="openModal" max-width="500">
      <VCard >
        <VCardTitle :class="`text-h5 bg-${color} text-center pa-4`">
          Aviso
        </VCardTitle>

        <VCardText class="text-center pt-6">
          <h5 class="text-h5">¿Estás seguro que deseas pasar a {{itemPdf}}?</h5>
        </VCardText>

        <VCardActions class="pb-5">
          <VSpacer></VSpacer>
          
          <VBtn 
            color="secondary" 
            large 
            @click="closeModalConfirmFactura"
          >
            Cancelar
          </VBtn>
          
          <VBtn 
            :color="color" 
            large 
            @click="convertirFactura"
            v-if="itemPdf == 'Factura'"
          >
            Confirmar
          </VBtn>

          <VBtn 
            :color="color" 
            large 
            @click="convertirNota"
            v-if="itemPdf == 'Nota'"
          >
            Confirmar
          </VBtn>
          
          <VSpacer></VSpacer>
        
        </VCardActions>
      </VCard>
    </VDialog>
  </VRow>

</template>


<script>
  
  export default {
    props: {
      itemPdf: String,
      modalConfirm: Boolean,
      closeModalConfirmFactura: Function,
      convertirFactura: Function,
      convertirNota: Function,
      color: String
    },

    data() {
      return {
        openModal: false
      }
    },

    watch: {
      modalConfirm(val) {
        this.openModal = val
      }
    }
  }

</script>


<style>

  .stylesDelete{
    height: 10vw;
    text-align: center;
  }

  /*.aviso{
    font-weight: bold !important;
    text-align: center !important;
    background: #FDCB25;
    padding: 15px !important;
    color:black!important;
  }*/
  
</style>
