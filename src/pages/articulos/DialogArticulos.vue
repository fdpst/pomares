<template>
  <VDialog v-model="dialog" width="500px">
    <DatosArticulo
      :venta="venta"
      v-model="servicios"
      :hide="true"
      titulo="Asignar Cuenta Contable"
      @saved="saved"
    ></DatosArticulo>
  </VDialog>
</template>

<script>
import DatosArticulo from "./DatosArticulo.vue";
export default {
  props: ["modelValue", "servicio", "venta"],
  components: { DatosArticulo },
  data() {
    return {
      servicios: {
        nro: null,
        id: "",
        nombre: "",
        email: "",
        telefono: "",
        cuenta_contable: {},
        user_id: localStorage.getItem("user_id"),
      },
      dialog: false,
    };
  },

  created() {
    this.dialog = this.modelValue;
    if (this.servicio != null) this.servicios = this.servicio;
  },
  watch: {
    servicio: {
      deep: true,
      handler(val) {
        if (val != null) {
          this.servicios = val;
        }
      },
    },
    modelValue(val) {
      this.dialog = val;
    },
    dialog(val) {
      this.$emit("update:modelValue", val);
    },
  },
  methods: {
    saved(res) {
      $toast.sucs("Artículo guardado con éxito");
      this.$emit("saved", res);
    },
  },
  computed: {
    isloading() {
      return this.$store.getters.getloading;
    },

    errors() {
      return this.$store.getters.geterrors;
    },
  },
};
</script>
