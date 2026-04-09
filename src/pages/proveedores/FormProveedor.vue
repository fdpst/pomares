<template>
  <VCard :title="$route.query.id ? 'Editar proveedor' : 'Crear proveedor'">
    <VDivider></VDivider>

    <loader v-if="isloading"></loader>

    <VCardText>
      <VForm class="mt-5">
        <VRow dense>
          <VCol cols="12" md="2">
            <VTextField
              filled
              label="N°. Proveedor"
              v-model="proveedor.nro_proveedor"
            ></VTextField>
          </VCol>

          <VCol cols="12" md="4">
            <VTextField
              filled
              :error-messages="
                errors.errors.nombre
                  ? errors.errors.nombre[0]
                  : null
              "
              v-model="proveedor.nombre"
              label="Nombre"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="6 ">
            <VTextField
              filled
              :error-messages="
                errors.errors.email ? errors.errors.email[0] : null
              "
              v-model="proveedor.email"
              label="Email"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="4">
            <VTextField
              filled
              :error-messages="
                errors.errors.telefono
                  ? errors.errors.telefono[0]
                  : null
              "
              v-model="proveedor.telefono"
              label="Teléfono"
              :rules="[rules.number_rule]"
              counter
              maxlength="9"
              required
            >
            </VTextField>
          </VCol>

          <VCol cols="12" md="4">
            <VTextField
              filled
              :error-messages="
                errors.errors.cif ? errors.errors.cif[0] : null
              "
              v-model="proveedor.cif"
              label="CIF"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="4">
            <VAutocomplete
              filled
              :error-messages="
                errors.errors.cif
                  ? errors.errors.id_provincia[0]
                  : null
              "
              v-model="proveedor.id_provincia"
              label="Provincia"
              :items="provincias"
              item-title="nombre"
              item-value="id"
              required
            ></VAutocomplete>
          </VCol>

          <!-- <VCol cols="12" md="4">
            <CuentaContableEditor
              :nombre="proveedor.nombre"
              :nro="proveedor.nro_proveedor"
              v-model="proveedor.cuenta_contable"
            ></CuentaContableEditor>
          </VCol> -->

          <VCol cols="12" md="6">
            <VTextField
              filled
              :error-messages="
                errors.errors.direccion
                  ? errors.errors.direccion[0]
                  : null
              "
              v-model="proveedor.direccion"
              label="Dirección"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="3">
            <VTextField
              filled
              :error-messages="
                errors.errors.cp ? errors.errors.cp[0] : null
              "
              v-model="proveedor.cp"
              label="Código postal"
              :rules="[rules.number_rule]"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="3">
            <VTextField
              filled
              :error-messages="
                errors.errors.localidad
                  ? errors.errors.localidad[0]
                  : null
              "
              v-model="proveedor.localidad"
              label="Localidad"
              required
            >
            </VTextField>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>

    <VDivider class="mt-5"></VDivider>

    <div class="pa-5">
      <VRow>
        <VCol cols="12">
          <VBtn
            rounded="pill"
            @click="saveProveedor"
            :disabled="isloading"
            class="mr-2"
            >Guardar</VBtn
          >

          <VBtn
              rounded="pill"
              to="/lista-proveedores/"
              :disabled="isloading"
              color="secondary"
              >Cancelar</VBtn
            >
        </VCol>
      </VRow>
    </div>
  </VCard>
</template>

<script>
import CuentaContableEditor from "@/components/CuentaContableEditor.vue";
export default {
  components: { CuentaContableEditor },
  data() {
    return {
      proveedor: {
        id: "",
        nombre: "",
        email: "",
        telefono: "",
        cif: "",
        direccion: "",
        cp: "",
        localidad: "",
        id_provincia: null,
        cuenta: "",
        user_id: localStorage.getItem("user_id"),
        nro_proveedor: null
      },
      rules: {
        number_rule: (value) => /^\d+$/.test(value) || "Campo numérico",
      },
      provincias: [],
      nueva: 0,
      cuentas: [],

      dialog: false,
    };
  },

  created() {
    if (this.$route.query.id) {
      this.getProveedorById(this.$route.query.id);
    }else {
      this.getLastId();
    }
    this.getProvincias();
    this.getCuentas();
  },

  methods: {
    getProveedorById(proveedor_id) {
      axios.get(`api/get-proveedor-by-id/${proveedor_id}`).then(
        (res) => {
          this.proveedor = res.data;
          this.proveedor.nro_proveedor =
            this.proveedor.nro_proveedor ?? this.proveedor.id;
        },
        (res) => {
          $toast.error("Error consultando proveedor");
        }
      );
    },
    saveProveedor() {
      axios.post("api/save-proveedor", this.proveedor).then(
        (res) => {
          $toast.sucs("Proveedor guardado con exito");
          this.$router.push(
            `/lista-proveedores/`
          );
        },
        (res) => {
          $toast.error("Error guardando proveedor");
        }
      );
    },
    getProvincias() {
      axios.get(`api/get-provincias`).then(
        (res) => {
          this.provincias = res.data;
        },
        (res) => {
          $toast.error("Error consultando proveedor");
        }
      );
    },
    // Get cuentas contables
    getCuentas() {
      let prefix = 600;
      axios.get(`api/get-cuentas/${prefix}`).then(
        (res) => {
          this.cuentas = res.data.success;

          let numero = 0;
          this.cuentas.forEach((element) => {
            if (numero < element.numero) {
              numero = element.numero;
            }
          });

          this.proveedor.cuenta = parseInt(numero) + 1;
        },
        (res) => {}
      );
    },
    getLastId() {
      axios.get(`api/get-last-proveedor-id`).then(
        (res) => {
          this.proveedor.nro_proveedor = res.data.success + 1;
        },
        (res) => {
          $toast.error("Error el nro de cliente");
        }
      );
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
  watch: {
    //
  },
};
</script>
