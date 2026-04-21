<template>
  <VDialog v-model="dialog" max-width="960px" scrollable>
    <DialogCloseBtn @click="close" />
    <DatosArticulo
      :titulo="`${id != null ? 'Editar' : 'Nuevo'} ${titulo}`"
      v-model="servicios"
      @saved="saved"
      @close="close"
      :venta="$route.meta.venta"
    ></DatosArticulo>
  </VDialog> 
</template>

<script>
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import CuentaContableEditor from "@/components/CuentaContableEditor.vue";
import DatosArticulo from "./DatosArticulo.vue";

export default {
  mixins: [gestorClienteMixin],
  components: { 
    CuentaContableEditor, 
    DatosArticulo 
  },
  props: {
    value: {
      type: Boolean,
      default: false
    },
    titulo: {
      type: String,
      default: ''
    },
    id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      dialog: false,
      servicios: null,
      rules: {
        number_rule: (value) => /^\d+$/.test(value) || "Campo numérico",
      },
    };
  },

  created() {
    this.resetServicios();
    this.dialog = this.value;
    console.log("dialog", this.dialog);
    /*if (this.$route.query.id) {
      this.getServiciosById(this.$route.query.id);
    }*/
  },
  watch: {
    value(newVal) {
      this.dialog = newVal;
      if (newVal && !this.id) {
        this.resetServicios();
      }
    },
    id(newVal, oldVal) {
      if (newVal != oldVal) {
        if (newVal) {
          this.getServiciosById(newVal);
        } else {
          this.resetServicios();
        }
      }
    },
    effectiveUserId(newVal) {
      if (newVal) {
        if (this.servicios) {
          this.servicios.user_id = newVal;
        }
      }
    },
  },

  methods: {
    createDefaultServicio() {
      return {
        nro: null,
        id: "",
        cuenta_contable: {},
        user_id: this.effectiveUserId,
        descripcion: "",
        precio: "",
        iva_percent: 0,
        nombre: "",
        email: "",
        telefono: "",
      };
    },
    resetServicios() {
      this.servicios = this.createDefaultServicio();
    },
    getServiciosById(Servicios_id) {
      axios.get(`api/get-servicio-by-id/${Servicios_id}`).then(
        (res) => {
          this.servicios = res.data;
          if (!this.servicios.user_id) {
            this.servicios.user_id = this.effectiveUserId;
          }
          if (
            this.servicios.iva_percent === undefined ||
            this.servicios.iva_percent === null
          ) {
            this.servicios.iva_percent = 0;
          }
          if (this.servicios.cuenta_contable == null) {
            this.servicios.cuenta_contable = {};
          }
        },
        (res) => {
          $toast.error("Error consultando servicios");
        }
      );
    },
    saved(res) {
      $toast.sucs("servicios guardado con éxito");
      this.$emit('refresh')
      this.close();
      /*this.$router.push(
        `/${this.$route.meta.lista}/${this.servicios.user_id}`
      );*/
    },
    close() {
      this.dialog = false;
      this.$emit('close');
      this.resetServicios();
    }
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
