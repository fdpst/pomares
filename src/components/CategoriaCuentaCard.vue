<template>
  <VCard>
    <VCardTitle
      class="bg-info pa-5"
      >Cuentas contables</VCardTitle
    >
    <VCardText>
      <VRow class="mt-3">
        <VCol cols="12" md="6">
          <VTextField
            v-model="search"
            label="Buscar"
            prepend-icon="ri-menu-search-fill"
            dense
          ></VTextField>
        </VCol>
      </VRow>
      <VDataTable
        v-model="categoria"
        :headers="headers"
        class="elevation-1 mt-4"
        :return-object="true"
        :items="categorias_cuentas"
        :search="search"
        show-select
        select-strategy="single"
        dense
      ></VDataTable>
    </VCardText>
    <VCardActions class="justify-center pb-6 pt-2">
      <VBtn @click="$emit('accept', categoria)" color="info"
        >Aceptar</VBtn>
      
      <VBtn @click="$emit('cancel')" color="secondary">Cancelar</VBtn>
    
    </VCardActions>
  </VCard>
</template>

<script>
export default {
  props: ["value"],
  data() {
    return {
      categoria: null,
      headers: [
        { title: "Cuenta", value: "cuenta" },
        { title: "Denominación", value: "denominacion" },
      ],
      search: "",
      categorias_cuentas: [],
    };
  },
  created() {
    this.getValue();
    this.getCategoriaCuenta();
  },
  watch: {
    value(val) {
      this.getValue();
    },
    categorias_cuentas(val) {
      this.getValue();
    },
  },
  methods: {
    getValue() {
      if (this.value != null) {
        const val = this.value;
        this.categoria = [
          this.categorias_cuentas.find((ele) => ele.id == val),
        ];
      }
    },
    // Get categoria cuentas contables
    getCategoriaCuenta() {
      axios.get(`/api/get-categoria-cuenta-contable`).then(
        (res) => {
          this.categorias_cuentas = res.data.success;
        },
        (res) => {}
      );
    },
  },
};
</script>
