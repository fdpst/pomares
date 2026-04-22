<template>

  <VRow>

    <VCol md="10" cols="12">
  
      <VCard title="Exportar a Excel Facturas Periodo">

        <VDivider></VDivider>

        <VContainer>

          <VRow dense class="mt-4">
            <VCol cols="12">
              <VAutocomplete
                label="Tipo"
                :items="[
                  {
                    value: 'emitidas',
                    title: 'Emitidas',
                  },
                  {
                    value: 'recibidas',
                    title: 'Autofacturas',
                  },
                  /*{
                    value: 3,
                    text: 'Resumen',
                  },*/
                ]"
                v-model="tipo"
              ></VAutocomplete>
            </VCol>
          </VRow>

          <VRow dense class="mt-4">
            <VCol cols="12" md="6">
              <AppDateTimePicker
                v-model="filter.desde"
                label="Desde"
                prepend-icon="ri-calendar-fill"
                readonly
              />
            </VCol>
            <VCol cols="12" md="6">
              <AppDateTimePicker
                v-model="filter.hasta"
                label="Hasta"
                prepend-icon="ri-calendar-fill"
                readonly
              />
            </VCol>
          </VRow>

          <VBtn
            rounded
            color="success"
            class="mt-4 mb-2 me-2"
            @click="downloadExcel()"
            >EXPORTAR DATOS A EXCEL<VIcon
              class="mr-2 white--text"
              color="white"
              >ri-file-line-excel</VIcon
            ></VBtn
          >
          <VBtn
            rounded
            color="success"
            class="mt-4 mb-2"
            @click="downloadPdf()"
            >EXPORTAR DATOS A PDF<VIcon
              class="mr-2 white--text"
              color="white"
              >ri-file-line-pdf-box</VIcon
            >
          </VBtn>

        </VContainer>

      </VCard>

    </VCol>

  </VRow>

</template>


<script>

export default {

  data() {
    return {
      tipo: "emitidas",
      filter: {
        desde: null,
        hasta: null,
      },
    };
  },
  created() {
    const year = new Date().getFullYear();
    this.filter.desde = `${year}-01-01`;
    this.filter.hasta = `${year}-12-31`;
  },
  methods: {
    downloadExcel() {
      axios
        .get(
          `api/print-${this.tipo}/${this.filter.desde}/${this.filter.hasta}?tipo=2`,
          {
            elementos: this.selected,
          }
        )
        .then((res) => {
          console.log(res.data);
          window.open(res.data, "_blank");
        });
    },
    downloadPdf() {
      axios
        .get(
          `api/print-${this.tipo}/${this.filter.desde}/${this.filter.hasta}`,
          {
            elementos: this.selected,
          }
        )
        .then((res) => {
          // Open the URL in a new tab
          window.open(res.data, "_blank");
        });
    },
  },
  computed: {
    isloading: function () {
      return this.$store.getters.getloading;
    },
  },
};
</script>
