<template>
  <div style="display: inline-flex; align-items: center;">
    <VBtn 
      :disabled="isloading" 
      rounded 
      depressed 
      @click="saveParteTrabajo" 
      color="success" 
      class="white--text me-2"
    >
      guardar
    </VBtn>

    <VBtn 
      v-if="recibo.id && !isEmpleado" 
      :disabled="isloading" 
      rounded 
      depressed 
      @click="saveFactura('factura', true)" 
      color="#5142A6"
      class="white--text me-2"
    >
      convertir a factura
    </VBtn>

    <VBtn 
      v-if="recibo.id" 
      :disabled="isloading" 
      rounded 
      depressed 
      @click="saveNota('nota', true)" 
      color="#5142A6"
      class="white--text"
    >
      convertir a nota
    </VBtn>
  </div>
</template>

<script>
  export default {
    props: ['recibo', 'tipo', 'isloading'],
    emits: ['save_parte_trabajo', 'convertir_factura', 'convertir_nota'],

    computed: {
      isEmpleado() {
        const role = parseInt(localStorage.getItem('role'));
        return role === 4;
      },
    },

    methods: {
      saveParteTrabajo() {
        this.$emit('save_parte_trabajo')
      },

      saveFactura() {
        this.$emit('convertir_factura')
      },

      saveNota() {
        this.$emit('convertir_nota')
      }
    }
  }
</script>
