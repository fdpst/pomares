<template>
  <VDialog v-model="dialog">
    <FormLibroDiarioVue
      :id_factura="id_factura"
      :id_factura_entrante="id_factura_entrante"
      :id_cliente="id_cliente"
      :id_proveedor="id_proveedor"
      :extra="extra"
      @close="dialog = false"
      :id_abono="id_abono"
      :abono="abono"
    ></FormLibroDiarioVue>
  </VDialog>
</template>
<script>
import FormLibroDiarioVue from "./FormLibroDiario.vue";
export default {
  components: {
    FormLibroDiarioVue,
  },
  props: [
    "value",
    "id_factura",
    "id_cliente",
    "id_proveedor",
    "extra",
    "id_abono",
    "abono",
    "id_factura_entrante",
  ],

  data() {
    return {
      dialog: false,
    };
  },
  created() {
    if (this.value != null) {
      this.dialog = this.value;
    }
  },
  watch: {
    dialog(val) {
      this.$emit("input", val);
    },
    value(val) {
      this.dialog = val;
    },
  },
};
</script>
