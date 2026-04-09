<template>
  <VCard
    class="pb-10"
    title="Lista Albaranes"
  >
    <div class="ps-5 pe-5 pb-5">
      <ConfirmDialog
        v-model="modalEliminar"
        @cancel="closeModal"
        @confirm="deleteNota"
      />

      <Loader v-if="isloading" />

      <VRow class="align-center">
        <VCol
          cols="12"
          md="8"
        >
          <div
            class="d-flex flex-column flex-md-row align-md-center"
            style="gap: 1rem;"
          >
            <VTextField
              v-model="search"
              prepend-icon="ri-user-search-fill"
              label="Búsqueda"
              class="flex-grow-1"
            />
          </div>
        </VCol>

        <VCol
          cols="12"
          md="4"
          class="text-end"
        >
          <VBtn
            rounded="pill"
            color="#5142A6"
            class="text-white mt-1"
            :to="{
              path: `/guardar-recibo`,
              query: { tipo: `nota` },
            }"
          >
            Nuevo
          </VBtn>
        </VCol>
      </VRow>

      <!-- Botones de acciones cuando hay elementos seleccionados -->
      <VRow
        v-if="selected.length > 0"
        class="mt-3 mb-3"
      >
        <VCol cols="12">
          <div
            class="d-flex flex-wrap align-center"
            style="gap: 0.75rem;"
          >
            <VBtn
              rounded="pill"
              color="primary"
              class="text-on-primary"
              @click="openAllPdfs"
            >
              Imprimir
            </VBtn>
            <VBtn
              v-if="!isEmpleado"
              rounded="pill"
              color="primary"
              class="text-on-primary"
              @click="printNotas(true)"
            >
              Imprimir Resumen
            </VBtn>
            <VBtn
              rounded="pill"
              color="primary"
              class="text-on-primary"
              @click="modalConfirm = true"
            >
              Enviar Mail
            </VBtn>
            <VBtn
              v-if="!isEmpleado"
              rounded="pill"
              color="primary"
              class="text-on-primary"
              @click="modalUnificar = true"
            >
              Unificar Albaranes ({{ selected.length }})
            </VBtn>
            <VBtn
              v-if="!isEmpleado"
              rounded="pill"
              color="primary"
              class="text-on-primary"
              @click="modalUnificarFactura = true"
            >
              Unificar y convertir a Factura ({{ selected.length }})
            </VBtn>
          </div>
        </VCol>
      </VRow>
    </div>

    <VDataTable
      v-model="selected"
      :headers="headers"
      :items="notas"
      :search="search"
      item-key="id"
      class="elevation-1 mt-5"
      :items-per-page="-1"
      :show-select="true"
      :return-object="true"
    >
      <template #item.nro_nota="{ item }">
        {{ item.nro_nota }}
      </template>
      <template #item.fecha="{ item }">
        {{ formatDateEs(item.fecha) }}
      </template>
      <template #item.pagado="{ item }">
        <VIcon v-if="item.pagado">
          ri-check-line
        </VIcon>
      </template>
      <template #item.total="{ item }">
        {{ formatPrice(item.total) }}€
      </template>
      <template #item.unificado="{ item }">
        <span v-if="item.unificado && item.nro_albaran_unificado">
          Sí (Albarán: {{ item.nro_albaran_unificado }})
        </span>
        <span v-else>
          No
        </span>
      </template>
      <template #item.archivo="{ item }">
        <a
          v-if="item.nombre_nota != null"
          target="_blank"
          :href="item.nota_path"
        >
          <VIcon
            medium
            color="#FF4C51"
            class="mr-2"
          >
            mdi mdi-file-pdf-box
          </VIcon>
        </a>
      </template>

      <template #item.action="{ item }">
        <RouterLink
          v-if="!item.unificado && !item.pagado"
          :to="{
            path: `/guardar-recibo`,
            query: {
              id: item.id,
              tipo: 'nota',
            },
          }"
          class="action-buttons"
        >
          <VIcon
            small
            class="mr-2 icono-primario"
          >
            ri-pencil-line
          </VIcon>
        </RouterLink>
        <VIcon
          v-else
          small
          class="mr-2 icono-primario"
          style="cursor: not-allowed; opacity: 0.4;"
          :title="item.unificado ? 'No se puede editar: albarán unificado' : 'No se puede editar: albarán facturado'"
        >
          ri-pencil-line
        </VIcon>

        <VIcon
          small
          class="mr-2"
          color="red"
          @click="mostrarModalEliminar(item)"
        >
          ri-delete-bin-line
        </VIcon>
      </template>
    </VDataTable>

    <ConfirmDialog
      v-model="modalConfirm"
      title="Enviar Lote Mail"
      text="¿Estás seguro que deseas enviarlos?"
      @cancel="modalConfirm = false"
      @confirm="SendMailLote"
    />

    <ConfirmDialog
      v-model="modalUnificar"
      title="Unificar Albaranes"
      text="¿Estás seguro que deseas unificar los albaranes seleccionados?"
      @cancel="modalUnificar = false"
      @confirm="unificarAlbaranes"
    />

    <ConfirmDialog
      v-model="modalUnificarFactura"
      title="Unificar y Convertir a Factura"
      text="¿Estás seguro que deseas unificar los albaranes seleccionados y convertirlos a factura?"
      @cancel="modalUnificarFactura = false"
      @confirm="unificarYConvertirAFactura"
    />
  </VCard>
</template>

<script>
import { formatPrice } from "@/@core/utils/formatters";

export default {
  data() {
    return {
      modalEliminar: false,
      modalConfirm: false,
      modalUnificar: false,
      modalUnificarFactura: false,
      item: "",
      search: "",
      notas_items: [],
      headers: [
        {
          title: "Nro. Albarán",
          value: "nro_nota",
        },
        {
          title: "Fecha",
          value: "fecha",
        },
        {
          title: "Cliente",
          value: "cliente.nombre",
        },
        {
          title: "Descripción",
          value: "observaciones",
        },
        {
          title: "total",
          value: "total",
        },
        {
          title: "Unificado",
          value: "unificado",
        },
        {
          title: "PDF",
          value: "archivo",
        },

        /*{
                    text: "Pagada",
                    value: "pagado",
                },*/
        {
          title: "Acciones",
          value: "action",
          sortable: false,
        },
      ],
      selected: [],
    }
  },
  computed: {
    isloading: function () {
      return this.$store.getters.getloading
    },
    notas: function () {
      // Solo las facturas pueden mostrarse como pendientes de pago (Lista de pendientes de pago / morosos)
      return this.notas_items
    },
    isEmpleado: function () {
      const role = parseInt(localStorage.getItem('role'))
      
      return role === 4
    },
  },
  created() {
    this.getNotas()
  },
  methods: {
    getNotas() {
      axios.get(`api/get-notas`).then(
        res => {
          this.notas_items = res.data
        },
        err => {
          this.$toast.error("Error consultando notas")
        },
      )
    },
    mostrarModalEliminar(item) {
      this.modalEliminar = true
      this.item = item
    },
    closeModal() {
      this.modalEliminar = false
      this.item = ""
    },
    deleteNota() {
      this.modalEliminar = false
      axios.get(`api/delete-nota/${this.item.id}`).then(
        res => {
          // Eliminar del array de datos subyacente
          const index = this.notas_items.findIndex(nota => nota.id === this.item.id)
          if (index !== -1) {
            this.notas_items.splice(index, 1)
          }
          this.$toast.sucs("Nota eliminada")
          this.item = ""
        },
        err => {
          this.$toast.error("Error eliminando nota")
        },
      )
    },
    openAllPdfs() {
      this.selected.forEach(nota => {
        if (nota.nota_path) {
          window.open(nota.nota_path, "_blank")
        }
      })
    },
    printNotas(resumen) {
      axios
        .post(`api/print-notas?${resumen ? "&resumen=true" : ""}`, {
          elementos: this.selected,
        })
        .then(res => {
          // Open the URL in a new tab
          window.open(res.data, "_blank")
        })
    },
    SendMailLote() {
      axios
        .post(`api/nota/mails`, {
          elementos: this.selected,
        })
        .then(res => {
          // Open the URL in a new tab
          //window.open(res.data, "_blank");
          this.modalConfirm = false
          this.$toast.sucs("Factura Enviadas con exito")
        })
    },
    unificarAlbaranes() {
      if (this.selected.length < 2) {
        this.$toast.error("Debes seleccionar al menos 2 albaranes para unificar")
        this.modalUnificar = false
        
        return
      }

      // Obtener los IDs de los albaranes seleccionados
      const albaranesIds = this.selected.map(item => item.id)

      axios
        .post(`api/unificar-albaranes`, {
          albaranes_ids: albaranesIds,
        })
        .then(res => {
          this.modalUnificar = false
          this.$toast.sucs("Albaranes unificados con éxito")
          this.selected = []
        })
        .catch(err => {
          this.modalUnificar = false

          const errorMessage = err.response?.data?.message || err.response?.data?.error || "Error al unificar albaranes"

          this.$toast.error(errorMessage)
        })
        .finally(() => {
          this.getNotas()
        })
    },
    unificarYConvertirAFactura() {
      if (this.selected.length < 2) {
        this.$toast.error("Debes seleccionar al menos 2 albaranes para unificar")
        this.modalUnificarFactura = false
        
        return
      }

      // Obtener los IDs de los albaranes seleccionados
      const albaranesIds = this.selected.map(item => item.id)

      axios
        .post(`api/unificar-y-convertir-factura`, {
          albaranes_ids: albaranesIds,
        })
        .then(res => {
          this.modalUnificarFactura = false
          this.$toast.sucs("Albaranes unificados y convertidos a factura con éxito")
          this.selected = []
        })
        .catch(err => {
          this.modalUnificarFactura = false

          const errorMessage = err.response?.data?.message || err.response?.data?.error || "Error al unificar y convertir albaranes a factura"

          this.$toast.error(errorMessage)
        })
        .finally(() => {
          this.getNotas()
        })
    },
    formatPrice,
  },
}
</script>
