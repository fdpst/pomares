<template>
  <VCard :title="$route.query.id ? 'Editar punto de venta' : 'Crear punto de venta'">
    <VDivider></VDivider>

    <loader v-if="isloading"></loader>

    <VCardText>
      <VForm class="mt-5">
        <VRow dense>
          <VCol cols="12" md="2">
            <VTextField
              filled
              label="N°. Punto de venta"
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

        <VRow dense class="mt-2">
          <VCol cols="12" md="6">
            <VTextField
              filled
              v-model="proveedor.nombre_comercial"
              label="Nombre comercial"
              hide-details="auto"
            />
          </VCol>
          <VCol cols="12" md="6">
            <VTextField
              filled
              v-model="proveedor.persona_contacto"
              label="Persona de contacto (opcional)"
              hide-details="auto"
            />
          </VCol>
          <VCol cols="12" md="6">
            <VSelect
              filled
              v-model="proveedor.catalogo_forma_pago_id"
              :items="catalogoFormasPago"
              item-title="descripcion"
              item-value="id"
              label="Forma de pago"
              clearable
              hide-details="auto"
            />
          </VCol>
          <VCol cols="12" md="6" class="d-flex align-end pb-1">
            <VBtn
              block
              rounded="pill"
              variant="flat"
              color="primary"
              prepend-icon="ri-bank-card-line"
              class="font-weight-medium"
              @click="abrirDialogCatalogoFormasPago">
              Gestionar formas de pago
            </VBtn>
          </VCol>
          <VCol cols="12" md="8">
            <VTextField
              filled
              v-model="proveedor.numero_cuenta"
              label="Número de cuenta (IBAN / cuenta bancaria)"
              hide-details="auto"
            />
          </VCol>
        </VRow>
      </VForm>
    </VCardText>

    <template v-if="proveedor.id">
      <VDivider class="mt-2"></VDivider>
      <VCardText class="pt-6">
        <p class="text-h6 mb-4">Comisiones por producto</p>
        <p class="text-body-2 text-medium-emphasis mb-4">
          Un mismo producto solo puede tener una comisión por punto de venta. Tipo
          <strong>%</strong> o importe fijo en <strong>€</strong>.
        </p>
        <VBtn
          rounded="pill"
          color="primary"
          class="mb-4"
          @click="abrirDialogComision(null)"
          >Nueva comisión</VBtn
        >
        <VDataTable
          :headers="comisionHeaders"
          :items="comisiones"
          item-key="id"
          class="elevation-1"
          density="comfortable">
          <template v-slot:item.producto="{ item }">
            {{ item.servicio?.descripcion ?? "—" }}
          </template>
          <template v-slot:item.tipo_txt="{ item }">
            {{ item.tipo === "porcentaje" ? "Porcentaje (%)" : "Importe (€)" }}
          </template>
          <template v-slot:item.valor_fmt="{ item }">
            {{
              item.tipo === "porcentaje"
                ? `${Number(item.valor)} %`
                : `${Number(item.valor)} €`
            }}
          </template>
          <template v-slot:item.acciones="{ item }">
            <VIcon
              small
              class="mr-2"
              color="grey-700"
              @click="abrirDialogComision(item)">
              ri-pencil-line
            </VIcon>
            <VIcon
              small
              color="red"
              @click="confirmarEliminarComision(item)">
              ri-delete-bin-line
            </VIcon>
          </template>
        </VDataTable>
      </VCardText>
    </template>
    <VCardText v-else class="pt-4">
      <VAlert type="info" variant="tonal" density="comfortable">
        Guarde el punto de venta para poder añadir comisiones por producto.
      </VAlert>
    </VCardText>

    <VDivider class="mt-5"></VDivider>

    <div class="pa-5">
      <VRow>
        <VCol cols="12">
          <VBtn
            rounded="pill"
            color="primary"
            @click="saveProveedor"
            :disabled="isloading"
            class="mr-2"
            >Guardar</VBtn
          >

          <VBtn
            rounded="pill"
            to="/lista-proveedores"
            :disabled="isloading"
            color="secondary"
            >Cancelar</VBtn
          >
        </VCol>
      </VRow>
    </div>

    <VDialog v-model="dialogComision" max-width="520" persistent>
      <VCard>
        <VCardTitle class="text-h6">{{
          comisionEditId ? "Editar comisión" : "Nueva comisión"
        }}</VCardTitle>
        <VCardText>
          <VAutocomplete
            class="mt-2"
            filled
            label="Producto (artículo de compra)"
            :items="serviciosComisionDisponibles"
            v-model="formComision.servicio_id"
            item-title="descripcion"
            item-value="id"
          />
          <VSelect
            class="mt-4"
            filled
            label="Tipo de comisión"
            v-model="formComision.tipo"
            :items="tiposComision"
            item-title="label"
            item-value="value"
          />
          <VTextField
            class="mt-4"
            filled
            type="number"
            step="0.01"
            min="0"
            :max="formComision.tipo === 'porcentaje' ? 100 : undefined"
            v-model.number="formComision.valor"
            :label="
              formComision.tipo === 'porcentaje'
                ? 'Porcentaje (%)'
                : 'Importe (€)'
            "
          />
        </VCardText>
        <VCardActions class="pb-4 px-4">
          <VSpacer />
          <VBtn
            rounded="pill"
            color="secondary"
            class="mr-2"
            @click="dialogComision = false"
            >Cancelar</VBtn
          >
          <VBtn rounded="pill" color="primary" @click="guardarComision"
            >Guardar</VBtn
          >
        </VCardActions>
      </VCard>
    </VDialog>

    <ConfirmDialog
      v-model="modalEliminarComision"
      title="Eliminar comisión"
      text="¿Eliminar esta comisión?"
      @cancel="modalEliminarComision = false"
      @confirm="eliminarComisionConfirmada"
      color="primary"
    />

    <VDialog v-model="dialogCatalogoFormasPago" max-width="580" scrollable>
      <VCard title="Formas de pago">
        <VDivider />
        <VCardText class="pt-4">
          <VRow dense class="align-end mb-4">
            <VCol cols="12" md="8">
              <VTextField
                v-model="nuevaFormaDescripcion"
                filled
                label="Nueva forma de pago"
                density="comfortable"
                hide-details
                @keyup.enter="guardarNuevaForma"
              />
            </VCol>
            <VCol cols="12" md="4">
              <VBtn block color="primary" @click="guardarNuevaForma">
                Añadir
              </VBtn>
            </VCol>
          </VRow>
          <VDataTable
            :headers="catalogoFormaHeaders"
            :items="catalogoFormasPago"
            item-value="id"
            density="compact"
            class="elevation-0 border rounded">
            <template #item.acciones="{ item }">
              <VIcon
                small
                class="mr-2"
                color="grey-700"
                @click="abrirEditarForma(item)">
                ri-pencil-line
              </VIcon>
              <VIcon
                small
                color="red"
                @click="confirmarEliminarForma(item)">
                ri-delete-bin-line
              </VIcon>
            </template>
          </VDataTable>
        </VCardText>
        <VCardActions class="pb-4">
          <VSpacer />
          <VBtn variant="text" @click="dialogCatalogoFormasPago = false">
            Cerrar
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog v-model="dialogEditarForma" max-width="440">
      <VCard title="Editar forma de pago">
        <VCardText>
          <VTextField
            v-model="formaEditDescripcion"
            filled
            label="Descripción"
            class="mt-2"
            hide-details="auto"
            @keyup.enter="guardarFormaEditada"
          />
        </VCardText>
        <VCardActions>
          <VSpacer />
          <VBtn variant="text" @click="dialogEditarForma = false">
            Cancelar
          </VBtn>
          <VBtn color="primary" @click="guardarFormaEditada">Guardar</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <ConfirmDialog
      v-model="modalEliminarForma"
      title="Eliminar forma de pago"
      text="Los puntos de venta que la usaban quedarán sin forma de pago asignada. ¿Continuar?"
      @cancel="modalEliminarForma = false"
      @confirm="eliminarFormaConfirmada"
      color="primary"
    />
  </VCard>
</template>

<script>
import CuentaContableEditor from "@/components/CuentaContableEditor.vue";
import ConfirmDialog from "@/components/ConfirmDialog.vue";
import gestorClienteMixin from "@/global_mixins/gestorClienteMixin.js";

export default {
  components: { CuentaContableEditor, ConfirmDialog },
  mixins: [gestorClienteMixin],
  data() {
    return {
      proveedor: {
        id: "",
        nombre: "",
        nombre_comercial: "",
        catalogo_forma_pago_id: null,
        numero_cuenta: "",
        persona_contacto: "",
        email: "",
        telefono: "",
        cif: "",
        direccion: "",
        cp: "",
        localidad: "",
        id_provincia: null,
        cuenta: "",
        user_id: localStorage.getItem("user_id"),
        nro_proveedor: null,
      },
      catalogoFormasPago: [],
      dialogCatalogoFormasPago: false,
      nuevaFormaDescripcion: "",
      dialogEditarForma: false,
      formaEditId: null,
      formaEditDescripcion: "",
      modalEliminarForma: false,
      formaPendienteBorrar: null,
      catalogoFormaHeaders: [
        { title: "Descripción", value: "descripcion", sortable: false },
        { title: "Acciones", value: "acciones", sortable: false, width: 120 },
      ],
      rules: {
        number_rule: (value) => /^\d+$/.test(value) || "Campo numérico",
      },
      provincias: [],
      nueva: 0,
      cuentas: [],

      dialog: false,
      comisiones: [],
      articulosCompra: [],
      dialogComision: false,
      comisionEditId: null,
      formComision: {
        servicio_id: null,
        tipo: "porcentaje",
        valor: null,
      },
      tiposComision: [
        { label: "Porcentaje (%)", value: "porcentaje" },
        { label: "Importe fijo (€)", value: "importe" },
      ],
      comisionHeaders: [
        { title: "Producto", value: "producto", sortable: false },
        { title: "Tipo", value: "tipo_txt", sortable: false },
        { title: "Comisión", value: "valor_fmt", sortable: false },
        { title: "Acciones", value: "acciones", sortable: false },
      ],
      modalEliminarComision: false,
      comisionPendienteBorrar: null,
    };
  },

  created() {
    this.proveedor.user_id = this.effectiveUserId;
    if (this.$route.query.id) {
      this.getProveedorById(this.$route.query.id);
    } else {
      this.getLastId();
    }
    this.getProvincias();
    this.getCuentas();
    this.cargarArticulosCompra();
    this.cargarCatalogoFormasPago();
  },

  methods: {
    abrirDialogCatalogoFormasPago() {
      this.dialogCatalogoFormasPago = true;
      this.cargarCatalogoFormasPago();
    },
    cargarCatalogoFormasPago() {
      axios.get("api/catalogo-formas-pago").then(
        (res) => {
          this.catalogoFormasPago = res.data || [];
        },
        () => {
          this.catalogoFormasPago = [];
          $toast.error("Error cargando formas de pago");
        }
      );
    },
    guardarNuevaForma() {
      const d = (this.nuevaFormaDescripcion || "").trim();
      if (!d) {
        return $toast.error("Indique una descripción");
      }
      axios
        .post("api/catalogo-formas-pago", {
          descripcion: d,
          user_id: this.effectiveUserId,
        })
        .then(() => {
          this.nuevaFormaDescripcion = "";
          this.cargarCatalogoFormasPago();
          $toast.sucs("Forma de pago añadida");
        })
        .catch((err) => {
          const msg =
            err.response?.data?.errors?.descripcion?.[0] ||
            err.response?.data?.message ||
            "No se pudo guardar";
          $toast.error(msg);
        });
    },
    abrirEditarForma(item) {
      this.formaEditId = item.id;
      this.formaEditDescripcion = item.descripcion || "";
      this.dialogEditarForma = true;
    },
    guardarFormaEditada() {
      const d = (this.formaEditDescripcion || "").trim();
      if (!d || !this.formaEditId) {
        return $toast.error("Indique una descripción");
      }
      axios
        .put(`api/catalogo-formas-pago/${this.formaEditId}`, {
          descripcion: d,
          user_id: this.effectiveUserId,
        })
        .then(() => {
          this.dialogEditarForma = false;
          this.cargarCatalogoFormasPago();
          $toast.sucs("Forma de pago actualizada");
        })
        .catch((err) => {
          const msg =
            err.response?.data?.errors?.descripcion?.[0] ||
            err.response?.data?.message ||
            "No se pudo actualizar";
          $toast.error(msg);
        });
    },
    confirmarEliminarForma(item) {
      this.formaPendienteBorrar = item;
      this.modalEliminarForma = true;
    },
    eliminarFormaConfirmada() {
      this.modalEliminarForma = false;
      if (!this.formaPendienteBorrar) return;
      const id = this.formaPendienteBorrar.id;
      axios
        .delete(`api/catalogo-formas-pago/${id}`)
        .then(() => {
          if (this.proveedor.catalogo_forma_pago_id === id) {
            this.proveedor.catalogo_forma_pago_id = null;
          }
          this.cargarCatalogoFormasPago();
          $toast.sucs("Forma de pago eliminada");
        })
        .catch(() => {
          $toast.error("Error al eliminar");
        });
      this.formaPendienteBorrar = null;
    },
    onClienteChanged() {
      this.proveedor.user_id = this.effectiveUserId;
      this.getProvincias();
      this.getCuentas();
      this.cargarArticulosCompra();
      this.cargarCatalogoFormasPago();
      if (this.$route.query.id) {
        this.getProveedorById(this.$route.query.id);
      }
    },
    cargarArticulosCompra() {
      axios.get(`api/get-servicios?venta=0`).then(
        (res) => {
          this.articulosCompra = res.data || [];
        },
        () => {
          this.articulosCompra = [];
        }
      );
    },
    loadComisiones() {
      if (!this.proveedor.id) {
        this.comisiones = [];
        return;
      }
      axios
        .get(`api/proveedor-comisiones/${this.proveedor.id}`)
        .then((res) => {
          this.comisiones = res.data || [];
        })
        .catch(() => {
          this.comisiones = [];
          $toast.error("Error cargando comisiones");
        });
    },
    abrirDialogComision(row) {
      if (!this.proveedor.id) {
        return $toast.error("Guarde el punto de venta antes de añadir comisiones");
      }
      if (row) {
        this.comisionEditId = row.id;
        this.formComision = {
          servicio_id: row.servicio_id,
          tipo: row.tipo,
          valor: Number(row.valor),
        };
      } else {
        this.comisionEditId = null;
        this.formComision = {
          servicio_id: null,
          tipo: "porcentaje",
          valor: null,
        };
      }
      this.dialogComision = true;
    },
    guardarComision() {
      if (!this.formComision.servicio_id && this.formComision.servicio_id !== 0) {
        return $toast.error("Seleccione un producto");
      }
      const v = parseFloat(this.formComision.valor);
      if (Number.isNaN(v) || v < 0) {
        return $toast.error("Indique un valor válido");
      }
      if (this.formComision.tipo === "porcentaje" && v > 100) {
        return $toast.error("El porcentaje no puede superar 100");
      }
      const payload = {
        proveedor_id: this.proveedor.id,
        servicio_id: this.formComision.servicio_id,
        tipo: this.formComision.tipo,
        valor: v,
        user_id: this.effectiveUserId,
      };
      if (this.comisionEditId) {
        axios
          .post(`api/proveedor-comisiones-update/${this.comisionEditId}`, payload)
          .then(() => {
            $toast.sucs("Comisión actualizada");
            this.dialogComision = false;
            this.loadComisiones();
          })
          .catch((err) => {
            const msg =
              err.response?.data?.errors?.servicio_id?.[0] ||
              err.response?.data?.error ||
              "Error al guardar";
            $toast.error(msg);
          });
      } else {
        axios
          .post("api/proveedor-comisiones", payload)
          .then(() => {
            $toast.sucs("Comisión guardada");
            this.dialogComision = false;
            this.loadComisiones();
          })
          .catch((err) => {
            const msg =
              err.response?.data?.errors?.servicio_id?.[0] ||
              err.response?.data?.error ||
              "Error al guardar";
            $toast.error(msg);
          });
      }
    },
    confirmarEliminarComision(item) {
      this.comisionPendienteBorrar = item;
      this.modalEliminarComision = true;
    },
    eliminarComisionConfirmada() {
      this.modalEliminarComision = false;
      if (!this.comisionPendienteBorrar) return;
      axios
        .get(`api/delete-proveedor-comision/${this.comisionPendienteBorrar.id}`)
        .then(() => {
          $toast.sucs("Comisión eliminada");
          this.loadComisiones();
        })
        .catch(() => {
          $toast.error("Error al eliminar");
        });
      this.comisionPendienteBorrar = null;
    },
    getProveedorById(proveedor_id) {
      axios.get(`api/get-proveedor-by-id/${proveedor_id}`).then(
        (res) => {
          this.proveedor = res.data;
          this.proveedor.nro_proveedor =
            this.proveedor.nro_proveedor ?? this.proveedor.id;
          this.loadComisiones();
        },
        () => {
          $toast.error("Error consultando punto de venta");
        }
      );
    },
    saveProveedor() {
      this.proveedor.user_id = this.effectiveUserId;
      axios.post("api/save-proveedor", this.proveedor).then(
        (res) => {
          $toast.sucs("Punto de venta guardado con éxito");
          const data = res.data;
          const id = data?.id;
          if (id) {
            this.proveedor = { ...this.proveedor, ...data };
            this.$router.replace({
              path: "/guardar-proveedor",
              query: { id: String(id) },
            });
            this.loadComisiones();
          } else {
            this.$router.push(`/lista-proveedores`);
          }
        },
        () => {
          $toast.error("Error guardando punto de venta");
        }
      );
    },
    getProvincias() {
      axios.get(`api/get-provincias`).then(
        (res) => {
          this.provincias = res.data;
        },
        () => {
          $toast.error("Error consultando provincias");
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
        () => {}
      );
    },
    getLastId() {
      axios.get(`api/get-last-proveedor-id`).then(
        (res) => {
          this.proveedor.nro_proveedor = res.data.success + 1;
        },
        () => {
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
    effectiveUserId() {
      const role = parseInt(localStorage.getItem("role"), 10);
      const selectedCliente = localStorage.getItem("selected_cliente_id");
      if (role === 3 && selectedCliente) {
        return selectedCliente;
      }
      return localStorage.getItem("user_id");
    },
    serviciosComisionDisponibles() {
      const usados = new Set(
        (this.comisiones || [])
          .filter((c) => !this.comisionEditId || c.id !== this.comisionEditId)
          .map((c) => c.servicio_id)
      );
      const sel = this.formComision?.servicio_id;
      return (this.articulosCompra || []).filter(
        (s) => !usados.has(s.id) || s.id === sel
      );
    },
  },
  watch: {
    //
  },
};
</script>
