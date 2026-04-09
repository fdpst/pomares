<template>
  <div>
    <div class="d-flex justify-space-between align-center">
      <p class="mt-8 mb-3"><strong>HISTORIAL</strong></p>

    <VBtn
      @click="form_historial = true"
      rounded="pill"
      color="primary"
        >nuevo</VBtn
      >
    </div>

    <VDataTable
      dense
      :headers="headers"
      :items="local_historial"
      item-key="id"
      class="elevation-1"
    >

      <template v-slot:item.action="{ item }">
        <VIcon
          small
          class="mr-2"
          @click="setItem(item)"
        >
          ri-pencil-line
        </VIcon>
        <VIcon
          @click="confirmdialog(item)"
          small
          class="mr-2"
        >
          ri-delete-bin-line
        </VIcon>
      </template>

    </VDataTable>
  </div>


    <VDialog v-model="form_historial" max-width="1000" class="historial-dialog">
      <VCard>

        <VCardTitle class="text-h5 text-center pa-5">
          {{ item_historial.id ? 'Editar Historial' : 'Guardar Historial' }}
        </VCardTitle>

        <VCardText>
          <VContainer>
            <VForm class="mt-4">

              <VRow dense>
                <VCol cols="12">
                  <AppDateTimePicker
                    v-model="item_historial.fecha"
                    label="Fecha"
                    prepend-icon="ri-calendar-fill"
                  />
                </VCol>
              </VRow>

              <VRow dense>
                <VCol cols="12">
                  <VTextarea
                    v-model="item_historial.observaciones"
                    outlined
                    label="Observaciones"
                  ></VTextarea>
                </VCol>
              </VRow>

            </VForm>
          </VContainer>
        </VCardText>


        <VCardActions class="justify-center pb-4">
          <VBtn
            rounded="pill"
            color="secondary"
            large
            class="btn-cancelar-historial"
            @click="() => {form_historial = false; resetForm()}"
            >Cancelar</VBtn
          >
          <VBtn
            rounded="pill"
            large
            color="primary"
            class="btn-confirmar-historial"
            @click="saveHistorial"
            >Confirmar</VBtn
          >
        </VCardActions>


      </VCard>
    </VDialog>

    <ConfirmDialog
      v-model="dialog"
      v-on:confirm="deleteItem"
      v-on:cancel="resetForm"
      color="primary"
    ></ConfirmDialog>
</template>


<script>

export default {
  props: ["historial", "cliente_id"],


  data() {
    return {
      form_historial: false,
      local_historial: [],
      menu: false,
      item_historial: {
        cliente_id: null,
        fecha: new Date().toISOString().substr(0, 10),
        observaciones: null,
      },
      headers: [
        {
          title: "Fecha",
          value: "format_fecha",
          width: "150",
        },
        {
          title: "Observaciones",
          value: "observaciones",
        },
        {
          title: "",
          value: "action",
        },
      ],

      dialog: false
    };
  },

  watch: {
    historial(n) {
      this.local_historial = JSON.parse(JSON.stringify(n));
    },
  },

  methods: {
    saveHistorial() {
      axios
        .post(
          `api/save-cliente-historial/${this.cliente_id}`,
          this.item_historial
        )
        .then(
          (res) => {
            this.updateOrPush(res.data);
            this.resetForm();
            this.form_historial = false;
          },
          (res) => {
            $toast.error("Error guardando historial");
          }
        );
    },

    updateOrPush(item) 
    {
      let index = this.local_historial.findIndex((x) => x.id == item.id);

      if (index < 0) 
      {
        // agregar
        this.local_historial.unshift(item);
      }
    },

    confirmdialog(item) {
      this.item_historial = item;
      
      this.dialog = true;
    },

    setItem(item) {
      this.item_historial = item;
      this.form_historial = true;
    },

    deleteItem() {
      axios
        .get(`api/delete-cliente-historial/${this.item_historial.id}`)
        .then(
          (res) => {
            this.local_historial.splice(
              this.local_historial.indexOf(this.item_historial),
              1
            );
            this.resetForm();
            this.dialog = false;
          },
          (res) => {
            $toast.error("Error eliminando historial");
          }
        );
    },

    resetForm() {
      this.item_historial = {
        cliente_id: null,
        fecha: new Date().toISOString().substr(0, 10),
        observaciones: null,
      };

      this.dialog = false;
    },
  },
};
</script>
