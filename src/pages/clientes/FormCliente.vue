<template>
  <VCard :title="$route.query.id ? 'Editar cliente' : 'Crear cliente'">

    <VDivider></VDivider>

    <loader v-if="isloading"></loader>

    <VCardText>
      
      <VForm class="mt-5">

        <VRow>

          <VCol cols="12" md="5">
            <VTextField
              filled
              label="N°. Cliente"
              v-model="cliente.nro_cliente"
            ></VTextField>
          </VCol>

          <VCol cols="12" md="5">
            <AppDateTimePicker
              v-model="cliente.fecha_alta"
              label="Fecha Alta"
              prepend-icon="ri-calendar-fill"
            />
          </VCol>

          <VCol cols="12" md="2">
            <VSwitch
              :inset="false"
              :label="cliente.activo ? 'Activo' : 'Inactivo'"
              v-model="cliente.activo"
              class="switch-activo"
              color="#5142A6"
              style="float: right;"
            ></VSwitch>
          </VCol>

        </VRow>


        <VRow>
          <VCol cols="12" md="6">
            <VTextField
              filled
              :error-messages="
                errors.errors.nombre_comercial
                  ? errors.errors.nombre_comercial[0]
                  : null
              "
              v-model="cliente.nombre_comercial"
              label="Nombre comercial"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="6">
            <VTextField
              filled
              :error-messages="
                errors.errors.nombre
                  ? errors.errors.nombre[0]
                  : null
              "
              v-model="cliente.nombre"
              label="Nombre fiscal"
              required
            ></VTextField>
          </VCol>
        </VRow>


        <VRow>

          <VCol cols="12" md="4">
            <VTextField
              filled
              :error-messages="
                errors.errors.dni ? errors.errors.dni[0] : null
              "
              v-model="cliente.dni"
              label="CIF/DNI"
              required
            ></VTextField>
          </VCol>

          <VCol cols="12" md="4">
            <VTextField
              filled
              :error-messages="
                errors.errors.email ? errors.errors.email[0] : null
              "
              v-model="cliente.email"
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
              v-model="cliente.telefono"
              label="Teléfono"
              required
              :rules="[rules.number_rule]"
              counter
              maxlength="15"
            ></VTextField>
          </VCol>
        </VRow>


        <VRow dense>

          <VCol cols="12" md="8">
            <VTextField
              filled
              v-model="cliente.direccion"
              label="Dirección"
            ></VTextField>
          </VCol>

          <VCol cols="12" md="4">
            <VTextField
              filled
              :error-messages="
                errors.errors.codigo_postal
                  ? errors.errors.codigo_postal[0]
                  : null
              "
              v-model="cliente.codigo_postal"
              label="Código Postal"
              :rules="[rules.number_rule]"
              counter
              maxlength="5"
            ></VTextField>
          </VCol>

        </VRow>


        <VRow dense>

          <VCol cols="12" md="4">
            <VSelect
              v-on:input="cliente.provincia_id = null"
              filled
              v-model="cliente.pais_id"
              :items="paises"
              item-title="nombre"
              item-value="id"
              label="Pais"
            >
            </VSelect>
          </VCol>

          <VCol cols="12" md="4">
            <VSelect
              filled
              :error-messages="
                errors.errors.provincia_id
                  ? errors.errors.provincia_id[0]
                  : null
              "
              v-model="cliente.provincia_id"
              label="Provincia"
              :items="provincias_filter"
              item-title="nombre"
              item-value="id"
              required
            >
            </VSelect>
          </VCol>

          <VCol cols="12" md="4">
            <VTextField
              filled
              v-model="cliente.localidad"
              label="Localidad"
            ></VTextField>
          </VCol>
        </VRow>


        <VRow>
          <VCol cols="12">
            <div class="richtext-field">
              <label class="richtext-label">Observaciones</label>
              <RichTextComponent
              v-model="cliente.observaciones"
              />
            </div>
          </VCol>
        </VRow>


        <p class="mt-8 mb-3"><strong>PERSONA DE CONTACTO</strong></p>

        <VRow>
          <VCol cols="12" md="6">
            <VTextField
              filled
              label="Nombre"
              v-model="cliente.contacto_nombre"
            ></VTextField>
          </VCol>

          <VCol cols="12" md="6">
            <VTextField
              filled
              label="Teléfono"
              v-model="cliente.contacto_telefono"
            ></VTextField>
          </VCol>
        </VRow>


        <p class="mt-8 mb-3"><strong>MÉTODO DE PAGO</strong></p>
        
        <VRow>
          <!-- <VCol cols="12" md="7">
            <CuentaContableEditor
              :nro="cliente.nro_cliente"
              :nombre="cliente.nombre"
              v-model="cliente.cuenta_contable"
            ></CuentaContableEditor>
          </VCol> -->
          <VCol cols="12" md="6">
            <VTextField
              filled
              label="Banco"
              v-model="cliente.banco"
            ></VTextField>
          </VCol>
          <VCol cols="12" md="6">
            <VAutocomplete
              filled
              :items="formas_pago"
              item-title="descripcion"
              item-value="id"
              label="Forma de pago"
              v-model="cliente.forma_pago_id"
            ></VAutocomplete>
          </VCol>
        </VRow>
      </VForm>

      <historial-cliente
        v-show="cliente.id != null"
        :historial="cliente.historial"
        :cliente_id="cliente.id"
      ></historial-cliente>
    </VCardText>

    <VDivider class="mt-5"></VDivider>

    <div class="pa-5">
      <VRow>
        <VCol cols="12">
          <VBtn
            rounded="pill"
            depressed
            @click="saveCliente"
            :disabled="isloading"
            class="mr-2"
            >Guardar</VBtn
          >

          <VBtn
            rounded
            depressed
            to="/lista-clientes/"
            :disabled="isloading"
            color="secondary"
            class="white--text"
            >Cancelar</VBtn
          >
        </VCol>
      </VRow>
    </div>
    

  </VCard>
  
</template>



<script>

import CuentaContableEditor from "@/components/CuentaContableEditor.vue";
import { provincias_mixin } from "@/global_mixins/provincias_mixin";
import historialCliente from "./historialCliente.vue";
import RichTextComponent from "@/pages/recibos/RichTextComponent.vue";

export default {
  mixins: [provincias_mixin],

  components: {
    historialCliente,
    CuentaContableEditor,
    RichTextComponent,
  },

  data() {
    return {
      cliente: {
        id: null,
        nro_cliente: null,
        nombre: "",
        nombre_comercial: "",
        fecha_alta: null,
        dni: "",
        email: "",
        telefono: "",
        direccion: "",
        provincia_id: null, 
        pais_id: 2,
        localidad: "",
        codigo_postal: "",
        observaciones: "",
        cuenta: "",
        banco: "",
        forma_pago_id: null,
        contacto_nombre: "",
        contacto_telefono: "",
        activo: true,
        user_id: localStorage.getItem("user_id"),
        historial: [],
      },
      rules: {
        number_rule: (value) => /^\d+$/.test(value) || "Campo numérico",
      },
      formas_pago: [],
      nueva: 0,
      cuentas: [],
    };
  },

  created() {
    this.getFormasPago();
    this.getCuentas();
    if (this.$route.query.id) {
      this.getClienteById(this.$route.query.id);
    } else {
      this.getLastId();
    }
  },

  methods: {

    getClienteById(cliente_id) 
    {
      axios.get(`api/get-cliente-by-id/${cliente_id}`).then(
        (res) => {
          this.cliente = res.data;

          this.cliente.activo = this.cliente.activo ? true : false

          this.cliente.observaciones = this.cliente.observaciones || "";

          this.cliente.nro_cliente = this.cliente.nro_cliente ?? this.cliente.id;
        },
        (res) => {
          $toast.error("Error consultando cliente");
        }
      );
    },

    saveCliente() 
    {
      axios.post("api/save-cliente", this.cliente).then(
        (res) => {
          $toast.sucs("Cliente guardado con exito");
          this.$router.push("/lista-clientes");
        },
        (res) => {
          $toast.error("Error guardando cliente");
        }
      );
    },

    getFormasPago() 
    {
      axios.get(`api/get-formas-pago`).then(
        (res) => {
          this.formas_pago = res.data.success;
        },
        (res) => {
          $toast.error("Error obteniendo las formas de pago");
        }
      );
    },

    getLastId() 
    {
      axios.get(`api/get-last-id`).then(
        (res) => {
          this.cliente.nro_cliente = res.data.success + 1;
        },
        (res) => {
          $toast.error("Error el nro de cliente");
        }
      );
    },

    getCuentas() 
    {
      let prefix = 400;
      axios.get(`api/get-cuentas/${prefix}`).then(
        (res) => {
          this.cuentas = res.data.success;

          let numero = 0;
          this.cuentas.forEach((element) => {
            if (numero < element.numero) {
              numero = element.numero;
            }
          });

          // this.proveedor.cuenta = parseInt(numero) + 1;
        },
        (res) => {}
      );
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
