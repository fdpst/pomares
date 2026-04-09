<template>
  <VRow>
    <VCol
      md="10"
      cols="12"
    >
      <VCard title="Guardar Ingreso">
        <VDivider />

        <VContainer>
          <Loader v-if="isloading" />
        </VContainer>

        <VForm ref="formRef">
          <VContainer>
            <!-- Información de la factura seleccionada (identi + tipo o selección) -->
            <VCard
              v-if="facturaInfo"
              variant="tonal"
              class="mb-5"
            >
              <VCardTitle class="text-subtitle-1">
                Datos de la factura
              </VCardTitle>
              <VCardText>
                <VRow dense>
                  <VCol cols="12">
                    <strong>Cliente:</strong> {{ facturaInfo.cliente }}
                  </VCol>
                  <VCol cols="6">
                    <strong>Total:</strong> {{ formatPrice(facturaInfo.total) }} €
                  </VCol>
                  <VCol cols="6">
                    <strong>Pagado:</strong> {{ formatPrice(facturaInfo.pagado) }} €
                  </VCol>
                  <VCol cols="12">
                    <strong>Deuda pendiente:</strong> {{ formatPrice(facturaInfo.deuda) }} €
                  </VCol>
                </VRow>
              </VCardText>
            </VCard>

            <p style="color: gray; font-weight: 600">
              Seleccione la factura o nota pendiente de pago a la que desea asociar el ingreso.
            </p>

            <VRow
              dense
              class="mt-5"
            >
              <VCol
                cols="12"
                md="6"
              >
                <VSelect
                  v-model="ingreso.codigo"
                  class="my-input"
                  :items="codigoOpciones"
                  item-title="text"
                  item-value="value"
                  filled
                  :error-messages="
                    errors.errors.codigo
                      ? errors.errors.codigo[0]
                      : null
                  "
                  label="Código (factura / nota)"
                  clearable
                  :loading="loadingMorosos"
                  @update:model-value="onCodigoChange"
                />
              </VCol>

              <VCol
                cols="12"
                md="6"
              >
                <VTextField
                  v-model.number="ingreso.importe"
                  type="number"
                  step="0.01"
                  min="0"
                  filled
                  :error-messages="importeErrorMessages"
                  label="Importe"
                  :hint="facturaInfo && ingreso.importe > 0 && Number(ingreso.importe) >= facturaInfo.deuda ? 'Al guardar, esta factura quedará completamente pagada.' : null"
                  persistent-hint
                />
              </VCol>
            </VRow>

            <VRow dense>
              <VCol cols="12">
                <VTextarea
                  v-model="ingreso.descripcion"
                  label="Descripción"
                />
              </VCol>
            </VRow>
          </VContainer>

          <VDivider class="mt-5" />

          <VContainer>
            <VRow>
              <VCol cols="12">
                <VBtn
                  rounded
                  depressed
                  :disabled="isloading"
                  color="primary"
                  class="white--text"
                  @click="saveIngreso"
                >
                  Guardar
                </VBtn>
                <VBtn
                  rounded
                  color="secondary"
                  class="white--text ms-2"
                  :disabled="isloading"
                  @click="back"
                >
                  volver
                </VBtn>
              </VCol>
            </VRow>
          </VContainer>
        </VForm>
      </VCard>
    </VCol>
  </VRow>
</template>

<script>
import { formatPrice } from '@/@core/utils/formatters';
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';

export default {
  mixins: [gestorClienteMixin],
  data() {
    return {
      ingreso: {
        id: "",
        codigo: "",
        importe: "",
        descripcion: "",
        user_id: null,
      },
      facturaInfo: null,
      morosos: [],
      loadingMorosos: false,
    }
  },
  computed: {
    isloading() {
      return this.$store.getters.getloading
    },
    errors() {
      return this.$store.getters.geterrors
    },
    effectiveUserId() {
      const role = parseInt(localStorage.getItem("role"))
      const selectedCliente = localStorage.getItem("selected_cliente_id")
      if (role === 3 && selectedCliente) {
        return selectedCliente
      }
      
      return localStorage.getItem("user_id")
    },
    codigoOpciones() {
      const opciones = this.morosos.map(m => {
        const codigo = (m.tipo === 'Factura' ? 'FAC' : 'NOT') + m.nro_factura
        
        return {
          value: codigo,
          text: `${codigo} - ${m.cliente || 'Sin cliente'} - Deuda: ${formatPrice(m.deuda)} €`,
        }
      })

      const values = opciones.map(o => o.value)
      if (this.ingreso.codigo && !values.includes(this.ingreso.codigo)) {
        opciones.unshift({
          value: this.ingreso.codigo,
          text: `${this.ingreso.codigo} (factura ya pagada o no encontrada)`,
        })
      }
      
      return opciones
    },
    importeErrorMessages() {
      if (this.errors?.errors?.importe?.length) {
        return this.errors.errors.importe
      }
      const importe = Number(this.ingreso.importe)
      if (this.facturaInfo && !isNaN(importe) && importe > 0 && importe > this.facturaInfo.deuda) {
        return [`El importe no puede superar la deuda pendiente (${formatPrice(this.facturaInfo.deuda)} €).`]
      }
      
      return null
    },
  },

  created() {
    this.ingreso.user_id = this.effectiveUserId

    if (this.$route.query.id) {
      this.getIngresoById(this.$route.query.id)
    }

    this.getMorosos().then(() => {
      if (this.$route.query.identi && this.$route.query.tipo) {
        this.loadFacturaPendienteInfo(this.$route.query.identi, this.$route.query.tipo)
      }
    })
  },

  methods: {
    formatPrice,
    back() {
      window.history.back()
    },
    getMorosos() {
      this.loadingMorosos = true
      
      return axios.get('api/get-morosos')
        .then(res => {
          this.morosos = res.data ?? []
        })
        .catch(() => {
          this.morosos = []
          $toast.error("Error consultando pendientes de pago")
        })
        .finally(() => {
          this.loadingMorosos = false
        })
    },
    loadFacturaPendienteInfo(identi, tipo) {
      return axios.get('api/get-factura-pendiente-info', { params: { identi, tipo } })
        .then(res => {
          this.facturaInfo = res.data
          this.ingreso.codigo = (res.data.tipo === 'Factura' ? 'FAC' : 'NOT') + res.data.nro_factura
        })
        .catch(() => {
          this.facturaInfo = null
          $toast.error("No se encontró la factura pendiente")
        })
    },
    onCodigoChange(codigo) {
      if (!codigo) {
        this.facturaInfo = null
        
        return
      }

      const m = this.morosos.find(
        m => ((m.tipo === 'Factura' ? 'FAC' : 'NOT') + m.nro_factura) === codigo,
      )

      this.facturaInfo = m || null
    },
    getIngresoById(ingreso_id) {
      axios.get(`api/get-ingreso-by-id/${ingreso_id}`).then(
        res => {
          this.ingreso = res.data
          this.ingreso.user_id = this.effectiveUserId
          this.getMorosos().then(() => {
            this.onCodigoChange(this.ingreso.codigo)
          })
        },
        () => {
          $toast.error("Error consultando Ingreso")
        },
      )
    },
    saveIngreso() {
      const importe = Number(this.ingreso.importe)
      if (this.facturaInfo && (!isNaN(importe) && importe > this.facturaInfo.deuda)) {
        $toast.error(`El importe no puede superar la deuda pendiente (${formatPrice(this.facturaInfo.deuda)} €).`)
        
        return
      }
      this.ingreso.user_id = this.effectiveUserId
      axios.post("api/save-ingreso", this.ingreso).then(
        () => {
          $toast.sucs("Ingreso guardado con exito")
          if (this.$route.query.identi && this.$route.query.tipo) {
            this.$router.push(`/morosos`)
          } else {
            this.$router.push("/lista-ingresos")
          }
        },
        err => {
          const msg = err.response?.data?.errors?.importe?.[0]
            || err.response?.data?.message
            || "Error guardando ingreso"

          $toast.error(msg)
        },
      )
    },
    onClienteChanged() {
      this.ingreso.user_id = this.effectiveUserId
      this.facturaInfo = null
      this.ingreso.codigo = ""
      this.getMorosos()
    },
  },
}
</script>

<style media="screen">
.my-input input {
    text-transform: uppercase;
}
</style>
