<template>
  <div>
    <div>
      <VSubheader>
        <div style="display: flex; flex-direction: row; align-items: center;">
          <h2>{{ title }}</h2>
          <VBtn
            v-if="readonly != true"
            style="margin-top: 10px"
            class="mx-2"
            fab
            dark
            small
            color="info"
            @click="openDialog()"
          >
            <VIcon color="white" dark> ri-add-line </VIcon>
          </VBtn>
        </div></VSubheader
      >
      <div class="mt-3">
        <VDataTable
          dense
          :headers="headers"
          :items="elementos"
          :items-per-page="15"
          item-key="id"
          class="elevation-1"
          :sort-desc="[false]"
        >
          <template v-slot:item.porcentaje="{ item }">
            {{ decimalComma(item.porcentaje) }}
          </template>
          <template v-slot:item.agente.nomina_bruto="{ item }">
            {{ format_precio(item.agente.nomina_bruto) }}
          </template>

          <template v-slot:item.total="{ item }">
            {{ format_precio(item.total) }}
          </template>
          <template v-slot:item.facturado="{ item }">
            {{ format_precio(item.facturado) }}
          </template>
          <template v-slot:item.pagado="{ item }">
            {{ format_precio(item.pagado) }}
          </template>
          <template v-slot:item.pendiente="{ item }">
            {{ format_precio(item.total - item.pagado) }}
          </template>

          <template v-slot:item.cuenta="{ item }">
            {{
              item.cuenta_contable
                ? item.cuenta_contable.numero
                : item.cuenta
            }}
          </template>
          <template v-slot:item.documento="{ item }">
            <a v-if="item.factura_id" :href="`api/pdf-factura/${item.factura_id}`" target="_blank">
              <VIcon
                small
                class="mr-2"
                color="red"
                style="font-size: 25px"
                title="PDF"
                >mdi mdi-pdf-box</VIcon
              >
            </a>
            <a v-if="item.factura_entrante_id" :href="`api/pdf-factura-entrante/${item.factura_entrante_id}`" target="_blank">
              <VIcon
                small
                class="mr-2"
                color="red"
                style="font-size: 25px"
                title="PDF"
                >mdi mdi-pdf-box</VIcon
              >
            </a>
          </template>

          <template v-slot:item.action="{ item }">
            <VIcon
              v-if="noedit == null"
              @click="openEditDialog(item)"
              small
              class="mr-2"
              color="#1d2735"
              style="font-size: 25px"
              title="EDITAR"
              >ri-pencil-line-outline</VIcon
            >
            <VIcon
              @click="openDeleteDialog(item)"
              small
              class="mr-2"
              color="red"
              style="font-size: 25px"
              title="BORRAR"
              >ri-delete-bin-line</VIcon
            >
          </template>
        </VDataTable>
      </div>
    </div>
    <!--CREATE DIRECCION-->


    <!-- <VDialog v-model="delete_dialog" max-width="500px">
      <VCard>
        <VCardTitle
          class="text-h5 aviso"
          style="
            justify-content: center;
            background: #1d2735;
            color: white;
          "
        >
          Aviso
        </VCardTitle>
        <VCardText style="text-align: center">
          <h2>¿Estás seguro que deseas eliminar?</h2>
        </VCardText>
        <VCardActions class="pt-3">
          <VSpacer></VSpacer>

          <VBtn color="error" large @click="delete_dialog = false"
            >Cancelar</VBtn
          >
          <VBtn color="success" large @click="deleteEstado()"
            >Confirmar</VBtn
          >
          <VSpacer></VSpacer>
        </VCardActions>
      </VCard>
    </VDialog> -->

    <ConfirmDialog
      v-model="delete_dialog"
      @cancel="delete_dialog = false"
      @confirm="deleteEstado"
    />



    <VDialog v-model="dialog" max-width="500px">
      <VCard>
        <VCardTitle
          class="text-h5 aviso"
          style="
            justify-content: center;
            background: #1d2735;
            color: white !important;
          "
        >
          Crear/Editar {{ title }}
        </VCardTitle>
        <VCardText style="text-align: center" class="pt-6">
          <v-form ref="form">
            <slot></slot>
          </v-form>
        </VCardText>
        <VCardActions class="pt-3">
          <VSpacer></VSpacer>
          <VBtn color="secondary" large @click="dialog = false"
            >Cancelar</VBtn
          >
          <VBtn
            v-if="update"
            color="success"
            large
            @click="updateEstado()"
            >Modificar</VBtn
          >
          <VBtn v-else color="success" large @click="createEstado()"
            >Guardar</VBtn
          >

          <VSpacer></VSpacer>
        </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>
<script>
export default {
  props: ["title", "elementos", "show", "headers", "noedit", "readonly"],
  data() {
    return {
      search: "",
      dialog: false,
      delete_dialog: false,
      update: false,
      index: -1,
      items: [],
      id: null,
    };
  },
  created() {
    if (this.elementos != null) {
      this.items = this.elementos;
    }
  },
  watch: {
    elementos: {
      deep: true,
      handler: function (val) {
        if (val != null) {
          this.item = [];
          this.item = val;
        }
      },
    },
  },
  methods: {
    closeDialog() {
      this.dialog = false;
      this.delete_dialog = false;
    },
    createEstado() {
      if (!this.$refs.form.validate()) {
        this.$custom_error("Debe llenar los campos requeridos");
        return;
      }
      this.$emit("create");
      this.closeDialog();
    },
    deleteEstado() {
      this.$emit("delete", this.index);
      this.closeDialog();
    },

    updateEstado() {
      if (!this.$refs.form.validate()) {
        this.$custom_error("Debe llenar los campos requeridos");
        return;
      }
      this.$emit("update");
      this.closeDialog();
    },
    openDeleteDialog(item) {
      this.delete_dialog = true;
      this.index = this.elementos.indexOf(item);
    },
    openDialog() {
      this.id = null;

      this.update = false;
      this.dialog = true;
    },

    openEditDialog(item) {
      this.index = this.elementos.indexOf(item);

      this.$emit("getEstado", this.index);
      this.update = true;
      this.dialog = true;
    },
    getIndexOfId(id) {
      for (let i = 0; i < this.elementos.length; i++) {
        if (this.elementos[i].id == id) {
          this.index = i;
          break;
        }
      }
    },
  },
  computed: {
    //
  },
};
</script>
