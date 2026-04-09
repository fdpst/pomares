<template>
  <div class="text-center">
   
    <VDialog
      v-model="dialog"
      
      persistent
      width="300">
      <VCard
        color="primary"
        dark>
        <VCardText>

          
          Escaneando archivos ...
          <VProgressLinear
            indeterminate
            color="white"
            class="mb-0"
          ></VProgressLinear>
        </VCardText>
      </VCard>
    </VDialog>
  </div>
</template>


<script>
  export default {
    data () {
      return {
        dialog: true,
      }
    },

  };
</script>