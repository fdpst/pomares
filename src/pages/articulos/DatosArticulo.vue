<template>
  <VCard :title="titulo" scrollable>
    <VDivider></VDivider>

    <VCardText>
      <VForm ref="form" v-model="formValid" class="mt-5">
        <loader v-if="isloading"></loader>

        <VRow dense>
          <VCol cols="12" md="2" v-if="!hide">
            <VTextField
              filled
              v-model="servicios.nro"
              label="Nro"
            ></VTextField>
          </VCol>

        <VCol cols="12" :md="hide ? 12 : 4" sm="12">
            <VTextField
              filled
              v-model="servicios.descripcion"
              label="Nombre"
              :rules="[requiredValidator]"
            ></VTextField>
          </VCol>

        <VCol cols="12" md="4" v-if="!hide">
            <VTextField
              filled
              v-model="servicios.precio"
              label="Precio"
              @input="servicios.precio = inputPrice($event)"
              suffix="€"
              :rules="[requiredValidator]"
            >
            </VTextField>
          </VCol>

        <VCol cols="12" md="4" v-if="!hide">
          <VSelect
            v-model="servicios.iva_percent"
            :items="array_iva"
            item-value="descripcion"
            item-title="descripcion"
            filled
            label="IVA (%)"
            :rules="[requiredValidator]"
          >
          </VSelect>
        </VCol>

          <!-- <VCol cols="12" :md="hide ? 12 : 4" sm="12">
            <CuentaContableEditor
              :vertical="true"
              :nombre="servicios.descripcion"
              v-model="servicios.cuenta_contable"
              :nro="servicios.nro"
            ></CuentaContableEditor>
          </VCol> -->
        </VRow>
      </VForm>
    </VCardText>

    <VDivider />

    <VCardActions class="justify-center mt-3">
      <VBtn
        rounded="pill"
        @click="$emit('close')"
        :disabled="isloading"
        color="secondary"
        class="btn-cancel-dialog mr-2"
      >
        Cancelar
      </VBtn>
      <VBtn
        rounded="pill"
        @click="saveServicios"
        :disabled="isloading"
        color="#DCFF2E"
        class="btn-confirm-dialog"
      >
        Aceptar
      </VBtn>
    </VCardActions>
  </VCard>
</template>

<script>
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import CuentaContableEditor from "@/components/CuentaContableEditor.vue";

export default {
  mixins: [gestorClienteMixin],
  props: ["modelValue", "venta", "titulo", "hide"],

  components: {
    CuentaContableEditor,
  },

  data() {
    return {
      formValid: false,
      servicios: {
        nro: null,
        cuenta_contable: {},
      },
      array_iva: [],
      rules: {
        number_rule: (modelValue) => /^\d+$/.test(modelValue) || "Campo numérico",
      },
    };
  },

  watch: {
    servicios: {
      deep: true,
      handler(val) {
        this.emitServicios();
        console.log(val, "servicios");
      },
    },

    modelValue: {
      deep: true,
      handler(val) {
        this.loadServicios();
        console.log(val, "modelValue");
      },
    },

  },

  created() {
    this.loadServicios();
    this.getArrayIva();
  },

  methods: {
    lastnumber() {
      const me = this;
      axios.get(`api/servicio/numero/${this.venta}`).then((res) => {
        console.log(res.data);
        me.servicios.nro = res.data + 1;
      });
    },
    loadServicios() {
      if (this.modelValue) {
        this.servicios = this.modelValue;
        if (this.servicios.nro == null || this.servicios.nro == "") {
          this.lastnumber();
        }
        if (!this.servicios.user_id) {
          this.servicios.user_id = this.effectiveUserId;
        }
        if (
          this.servicios.iva_percent === undefined ||
          this.servicios.iva_percent === null
        ) {
          this.servicios.iva_percent = 0;
        }
      } else {
        this.servicios = {
          nro: null,
          cuenta_contable: {},
          descripcion: "",
          precio: "",
          iva_percent: 0,
          user_id: this.effectiveUserId,
          nombre: "",
          email: "",
          telefono: "",
        };
        this.lastnumber();
      }
    },
    getArrayIva() {
      axios
        .get(`api/get-iva`)
        .then((res) => {
          this.array_iva = res.data?.success ?? [];
        })
        .catch(() => {
          $toast.error("Error consultando opciones de IVA");
        });
    },
    emitServicios() {
      this.$emit("update:modelValue", this.servicios);
    },
    saveServicios() {
      this.$refs.form.validate()
      if(this.formValid){
        this.servicios.venta = this.venta;
        this.servicios.user_id = this.effectiveUserId;
        axios.post("api/save-servicio", this.servicios).then(
          (res) => {
            this.$emit("saved", res.data);
          },
          (res) => {
            $toast.error("Error guardando servicios");
          }
        );
      }
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
