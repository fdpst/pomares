<template>
  <div>
    <VRow justify="center">
      <VDialog
        v-model="dialog"
        max-width="500"
        @click:outside="closeModal"
      >
        <VCard>
          <VCardTitle :class="`text-h5 bg-${color} text-center pa-4`">
            Aviso
          </VCardTitle>

          <VCardText class="text-center pt-6">
            <h5
              v-if="itemPdf == ', Factura'"
              class="text-h5 text-left"
            >
              ¿Deseas convertir este presupuesto a factura? Puedes asignar una serie si lo deseas.
            </h5>
            <h5
              v-else
              class="text-h5"
            >
              ¿Estás seguro que deseas pasar a {{ itemPdf }}?
            </h5>
            <!-- Selector de serie + botón crear serie (igual que en guardar-recibo tipo=factura) -->
            <p
              v-if="itemPdf == ', Factura'"
              class="text-caption text-medium-emphasis text-left mt-2 mb-1"
            >
              La serie no es obligatoria. Puedes continuar sin seleccionar ninguna.
            </p>
            <div
              v-if="itemPdf == ', Factura'"
              class="d-flex align-center mt-2"
            >
              <VSelect
                v-model="serieIdElegida"
                :items="series"
                item-title="serie"
                item-value="id"
                label="Serie de la factura (opcional)"
                placeholder="Selecciona una serie"
                clearable
                density="comfortable"
                class="flex-grow-1"
                hide-details
              />
              <VBtn
                icon="ri-add-line"
                size="small"
                class="ml-2"
                color="#5142A6"
                @click="openDialogNuevaSerie"
              />
            </div>
          </VCardText>

          <VCardActions class="pt-3">
            <VSpacer />
            
            <VBtn 
              :color="color" 
              variant="outlined"
              large 
              @click="closeModal"
            >
              Cancelar
            </VBtn>

            <VBtn 
              v-if="itemPdf == 'Factura'" 
              :color="color"
              variant="elevated"
              class="text-white" 
              large
              @click="convertirFacturaConfirmado"
            >
              Confirmar
            </VBtn>

            <VBtn 
              v-if="itemPdf == 'Nota'" 
              :color="color"
              variant="elevated"
              class="text-white" 
              large
              @click="$emit('convertirNotaConfirmado')"
            >
              Confirmar
            </VBtn>

            <VBtn 
              v-if="itemPdf == ', Factura'" 
              :color="color"
              variant="elevated"
              class="text-white" 
              large
              @click="onConfirmarPresupuestoAFactura"
            >
              Confirmar
            </VBtn>
            
            <VSpacer />
          </VCardActions>
        </VCard>
      </VDialog>

      <!-- Dialog Nueva serie: misma posición y color de botones que el diálogo principal -->
      <VDialog
        v-model="dialogNuevaSerie"
        width="500"
        persistent
      >
        <VCard>
          <DialogCloseBtn
            variant="text"
            size="default"
            @click="closeDialogNuevaSerie"
          />
          <VCardTitle class="text-h5 text-center pa-4">
            Nueva Serie
          </VCardTitle>
          <VCardText>
            <div class="text-body-1 text-center mb-4">
              Ingrese los datos de la Serie
            </div>
            <VForm
              ref="refFormNuevaSerie"
              @submit.prevent="onSubmitNuevaSerie"
            >
              <VTextField
                v-model="nuevaSerieNombre"
                label="Serie"
                :rules="[v => !!v || 'Campo obligatorio']"
                class="mb-4"
              />
              <VCardActions class="pt-0 px-0">
                <VSpacer />
                <VBtn
                  :color="color"
                  variant="outlined"
                  large
                  @click="closeDialogNuevaSerie"
                >
                  Cancelar
                </VBtn>
                <VBtn
                  type="submit"
                  :color="color"
                  variant="elevated"
                  class="text-white"
                  large
                >
                  Confirmar
                </VBtn>
                <VSpacer />
              </VCardActions>
            </VForm>
          </VCardText>
        </VCard>
      </VDialog>
    </VRow>
  </div>
</template>


<script>
import DialogCloseBtn from '@/@core/components/DialogCloseBtn.vue';

export default {
  components: {
    DialogCloseBtn,
  },
  props: {
    itemPdf: String,
    modalConfirm: Boolean,
    closeModal: Function,
    convertirFacturaConfirmado: Function,
    ConvertirPresupuestoAFactura: Function,
    color: {
      type: String,
      default: 'primary',
    },
    series: {
      type: Array,
      default: () => [],
    },
    createSeries: {
      type: Function,
      default: null,
    },
  },

  emits: ['convertirNotaConfirmado', 'confirmar-presupuesto-a-factura'],

  data() {
    return {
      dialog: false,
      serieIdElegida: null,
      dialogNuevaSerie: false,
      nuevaSerieNombre: '',
    }
  },

  watch: {
    modalConfirm(val) {
      this.dialog = val
      if (val && this.itemPdf === ', Factura') {
        this.serieIdElegida = null
      }
    },
  },

  methods: {
    onConfirmarPresupuestoAFactura() {
      this.$emit('confirmar-presupuesto-a-factura', this.serieIdElegida)
      this.closeModal()
      this.serieIdElegida = null
    },

    openDialogNuevaSerie() {
      this.nuevaSerieNombre = ''
      this.dialogNuevaSerie = true
    },

    closeDialogNuevaSerie() {
      this.dialogNuevaSerie = false
      this.nuevaSerieNombre = ''
    },

    async onSubmitNuevaSerie() {
      const { valid } = await this.$refs.refFormNuevaSerie?.validate()
      if (!valid) return
      if (!this.createSeries) return
      try {
        const created = await this.createSeries({ serie: this.nuevaSerieNombre })
        if (created?.id) {
          this.serieIdElegida = created.id
        }
        this.closeDialogNuevaSerie()
      } catch (e) {
        // Error ya mostrado por createSeries (toast)
      }
    },
  },
}
</script>


<style>
  .stylesDelete{
    height: 10vw;
      text-align: center;
  }
  .aviso{
    font-weight: bold !important;
    text-align: center !important;
    background: #FDCB25;
    padding: 15px !important;
    color:black!important;
  }
</style>
