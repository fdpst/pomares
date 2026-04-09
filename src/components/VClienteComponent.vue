<template>
  <VDialog @click:outside="closeDialog" v-bind="dialog" width="750">

    <VCard>
      <VCardTitle class="headline primary white--text" dark primary-title>
        Seleccionar Cliente
      </VCardTitle>

      <loader v-if="isloading"></loader>

      <VCardText class="px-3 py-3">
        <VRow>
          <VCol cols="12" md="3">
            <VTextField v-model="dni" label="DNI / CIF"></VTextField>
          </VCol>
          <VCol cols="12" md="4">
            <VTextField v-model="cliente.nombre" label="Nombre" :disabled="cliente.id != null"></VTextField>
          </VCol>
        </VRow>

        <VRow>
          <VCol cols="12" md="3">
            <VBtn rounded depressed @click="seleccionarcliente" v-if="cliente.id != null" class="white--text" color="light-blue">Aceptar</VBtn>
            <VBtn rounded depressed @click="savecliente" v-else class="white--text" color="green">Guardar</VBtn>
          </VCol>
        </VRow>

      </VCardText>

      <v-divider></v-divider>

      <VCardActions>
        <VSpacer></VSpacer>
        <VBtn color="primary" text @click="closeDialog">cerrar</VBtn>
      </VCardActions>
    </VCard>

  </VDialog>

</template>

<script>
  import debounce from 'lodash/debounce'
  export default {
    props: ['dialog'],
    data() {
      return {
        dni: null,
        cliente: {
          id: null,
          dni: null,
          nombre: null
        }
      }
    },
    watch: {
      dni(n) {
        if (n != '') {
          this.debouncedQuery(n)
        }
      },
    },
    methods: {
      debouncedQuery: debounce(function(n) {
        this.getclientedni(n);
      }, 750),

      getclientedni(n) {
        axios.get(`api/get-cliente-dni/${n}`).then(res => {
          this.cliente = res.data
        }, res => {
          this.$toast.error('Error consultando cliente')
        })
      },

      savecliente() {
        this.cliente.dni = this.dni
        axios.post('api/save-cliente', this.cliente).then(res => {
          this.cliente = res.data
        }, res => {
          this.$toast.error('Error guardando cliente')
        })
      },

      seleccionarcliente() {
        this.$emit('seleccionar_cliente', this.cliente)
      },

      closeDialog() {
        this.$emit('close_dialog')
      }
    },
    computed: {
      isloading: function() {
        return this.$store.getters.getloading
      },
    }
  }
</script>

<style scope>
  .v-application p {
    margin-bottom: 5px;
  }
</style>