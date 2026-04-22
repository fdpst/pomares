<template>
  <VCard class="pb-10" :title="`Lista de ${$route.meta.titulo}`">

    <div class="ps-5 pe-5 pb-5">
      
      <VRow>
        <VCol cols="12" md="8">
          <VTextField
            prepend-icon="ri-user-search-fill"
            v-model="search"
            label="Búsqueda"
          ></VTextField>
        </VCol>

        <VCol cols="12" md="4" class="text-end">
          <VBtn
            rounded
            depressed
            color="primary"
            class="mt-1"
            @click="openFormArticulo(null)"
            >Nuevo</VBtn
          >
        </VCol>
      </VRow>

      <VRow class="mt-2 align-end">
        <VCol cols="12" md="4">
          <AppDateTimePicker
            v-model="fechaDesde"
            label="Fecha desde"
            prepend-icon="ri-calendar-fill"
          />
        </VCol>
        <VCol cols="12" md="4">
          <AppDateTimePicker
            v-model="fechaHasta"
            label="Fecha hasta"
            prepend-icon="ri-calendar-fill"
          />
        </VCol>
        <VCol cols="12" md="4" class="d-flex align-center pb-2">
          <VBtn
            variant="text"
            color="secondary"
            size="small"
            @click="limpiarFiltroFechas"
          >
            Quitar filtro de fechas
          </VBtn>
        </VCol>
      </VRow>

    </div>


    <loader v-if="isloading || loadingServicios"></loader>


    <VDataTableServer
      :headers="headers"
      :items="servicios"
      :items-length="totalServicios"
      item-key="id"
      class="elevation-1 mt-2"
      v-model:items-per-page="pagination.itemsPerPage"
      v-model:page="pagination.page"
      @update:options="updateOptions"
    >
      <template v-slot:item.precio="{ item }">
        {{ formatPriceServicios(item.precio) }}€
      </template>
      <template v-slot:item.iva_percent="{ item }">
        {{ item.iva_percent ?? 0 }}{{ typeof item.iva_percent === 'string' && item.iva_percent.includes('%') ? '' : '%' }}
      </template>
      <template v-slot:item.created_at="{ item }">
        <span v-if="item.created_at != null">
          {{ formatDateEs(item.created_at) }}
        </span>
      </template>
      <template v-slot:item.action="{ item }">
        <VIcon
          v-if="$route.meta.venta === 0"
          small
          class="mr-2"
          color="primary"
          title="Historial de precio"
          @click="openLogPrecio(item)"
        >
          ri-history-line
        </VIcon>
        <VIcon small class="mr-2" color="grey-600" @click="openFormArticulo(item)">
            ri-pencil-line
          </VIcon>

        <VIcon
          @click="mostrarModalEliminar(item)"
          small
          class="mr-2"
          color="red"
        >
          ri-delete-bin-line
        </VIcon>
      </template>

      <template #bottom>
        <VDivider />

        <div class="d-flex justify-end flex-wrap gap-x-6 px-2 py-1">
          <div
            class="d-flex align-center gap-x-2 text-medium-emphasis text-base"
          >
            Filas por página:
            <VSelect
              v-model="pagination.itemsPerPage"
              class="per-page-select"
              variant="plain"
              :items="[10, 20, 25, 50, 100]"
            />
          </div>

          <p
            class="d-flex align-center text-base text-high-emphasis me-2 mb-0"
          >
            {{
              paginationMeta(
                {
                  page: pagination.page,
                  itemsPerPage: pagination.itemsPerPage,
                },
                totalServicios
              )
            }}
          </p>

          <div class="d-flex gap-x-2 align-center me-2">
            <VBtn
              class="flip-in-rtl"
              icon="ri-arrow-left-s-line"
              variant="text"
              density="comfortable"
              color="high-emphasis"
              :disabled="pagination.page <= 1"
              @click="
                pagination.page <= 1
                  ? (pagination.page = 1)
                  : pagination.page--
              "
            />

            <VBtn
              class="flip-in-rtl"
              icon="ri-arrow-right-s-line"
              density="comfortable"
              variant="text"
              color="high-emphasis"
              :disabled="
                pagination.page >=
                Math.ceil(totalServicios / pagination.itemsPerPage)
              "
              @click="
                pagination.page >=
                Math.ceil(totalServicios / pagination.itemsPerPage)
                  ? (pagination.page = Math.ceil(
                        totalServicios / pagination.itemsPerPage
                    ))
                  : pagination.page++
              "
            />
          </div>
        </div>
      </template>
    </VDataTableServer>
  </VCard>

  <FormArticulo :value="isFormArticuloVisible" :titulo="$route.meta.titulo" :id="selectedItemId" @close="closeFormArticulo" @refresh="getServicios" />
  
  <ConfirmDialog
    v-model="modalEliminar"
    color="primary"
    @cancel="closeModal"
    @confirm="deleteArticulo"
  />

  <VDialog v-model="dialogPrecioLog" max-width="780" scrollable>
    <VCard>
      <VCardTitle class="d-flex flex-wrap align-center justify-space-between gap-2">
        <span class="text-h6">Cambios de precio</span>
        <VBtn icon variant="text" density="comfortable" @click="dialogPrecioLog = false">
          <VIcon>ri-close-line</VIcon>
        </VBtn>
      </VCardTitle>
      <VCardSubtitle v-if="logPrecioDescripcion" class="pb-0 text-wrap">
        {{ logPrecioDescripcion }}
      </VCardSubtitle>
      <VCardText class="text-body-2 text-medium-emphasis pt-2 pb-0">
        El precio sustituido se muestra como vigente <strong>hasta el día anterior</strong> al cambio;
        el precio aplicado, <strong>desde el día del cambio</strong>. Si no hubo otro cambio después, el precio nuevo <strong>sigue vigente</strong>.
      </VCardText>
      <VCardText class="pt-4">
        <div v-if="loadingLogPrecio" class="text-center py-6 text-medium-emphasis">
          Cargando…
        </div>
        <template v-else-if="logPrecioItems.length">
          <VTable density="compact" class="border rounded">
            <thead>
              <tr>
                <th class="text-start">Cambio registrado</th>
                <th class="text-start">Precio anterior</th>
                <th class="text-start">Precio nuevo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, idx) in logPrecioItems" :key="idx">
                <td class="text-nowrap align-top">{{ formatFechaHoraPrecio(row.created_at) }}</td>
                <td class="align-top">
                  <div class="font-weight-medium text-end text-sm-start">
                    {{ formatPriceServicios(row.precio_anterior) }}€
                  </div>
                  <div class="text-caption text-medium-emphasis">
                    Vigente hasta el {{ row.vigenciaAnteriorHasta }}
                  </div>
                </td>
                <td class="align-top">
                  <div class="font-weight-medium text-end text-sm-start">
                    {{ formatPriceServicios(row.precio_nuevo) }}€
                  </div>
                  <div class="text-caption text-medium-emphasis">
                    Vigente desde el {{ row.vigenciaNuevoDesde }}
                    <template v-if="row.vigenciaNuevoHasta">
                      · hasta el {{ row.vigenciaNuevoHasta }}
                    </template>
                    <template v-else>
                      · <span class="text-high-emphasis">sigue vigente</span>
                    </template>
                  </div>
                </td>
              </tr>
            </tbody>
          </VTable>
        </template>
        <p v-else class="text-medium-emphasis mb-0">
          No hay cambios de precio registrados (solo se guardan al modificar el precio de un producto existente).
        </p>
      </VCardText>
    </VCard>
  </VDialog>
</template>

<script>
import debounce from 'lodash/debounce';
import { formatDateEs } from '@core/utils/formatters';
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js';
import { paginationMeta } from '@/utils/paginationMeta.js';
import FormArticulo from './FormArticulos.vue';

export default {
  mixins: [gestorClienteMixin],
  components: {
    FormArticulo
  },
  data() {
    return {
      isFormArticuloVisible: false,
      selectedItemId: null,
      modalEliminar: false,
      dialogPrecioLog: false,
      loadingLogPrecio: false,
      logPrecioItems: [],
      logPrecioDescripcion: "",
      item: "",
      search: "",
      fechaDesde: null,
      fechaHasta: null,
      servicios: [],
      totalServicios: 0,
      loadingServicios: false,
      pagination: {
        page: 1,
        itemsPerPage: 10,
      },
      headers: [
        {
          title: "Nro",
          value: "nro",
        },
        {
          title: "Descripción",
          value: "descripcion",
        },
        {
          title: "Precio",
          value: "precio",
        },
        {
          title: "IVA",
          value: "iva_percent",
        },
        {
          title: "Fecha",
          value: "created_at",
        },
        {
          title: "Acciones",
          value: "action",
          sortable: false,
        },
      ],
    };
  },
  created() {
    this.getServicios();
  },

  watch: {
    $route() {
      this.pagination.page = 1;
      this.getServicios();
    },
    search: {
      handler: function () {
        this.getServiciosDebounced();
      },
    },
    fechaDesde() {
      this.pagination.page = 1;
      this.getServicios();
    },
    fechaHasta() {
      this.pagination.page = 1;
      this.getServicios();
    },
    pagination: {
      handler() {
        this.getServicios();
      },
      deep: true,
    },
  },

  methods: {
    formatDateEs,
    paginationMeta,
    limpiarFiltroFechas() {
      this.fechaDesde = null;
      this.fechaHasta = null;
    },
    getServiciosDebounced: debounce(function () {
      this.pagination.page = 1;
      this.getServicios();
    }, 500),
    getServicios() {
      this.loadingServicios = true;
      axios
        .get('api/get-servicios', {
          params: {
            venta: this.$route.meta.venta,
            amount: this.pagination.itemsPerPage,
            page: this.pagination.page,
            search: this.search || undefined,
            fecha_desde: this.fechaDesde || undefined,
            fecha_hasta: this.fechaHasta || undefined,
          },
        })
        .then(
          (res) => {
            const data = res.data;
            if (data && typeof data.data !== 'undefined') {
              this.servicios = data.data || [];
              this.totalServicios = data.total ?? 0;
            } else {
              this.servicios = Array.isArray(data) ? data : [];
              this.totalServicios = this.servicios.length;
            }
            this.loadingServicios = false;
          },
          (err) => {
            $toast.error("Error consultando artículos");
            this.servicios = [];
            this.totalServicios = 0;
            this.loadingServicios = false;
          }
        );
    },
    updateOptions(options) {
      this.pagination.itemsPerPage = options.itemsPerPage;
      this.pagination.page = options.page;
    },
    onClienteChanged(event) {
      console.log('ListaArticulos: Cliente cambiado, recargando artículos...', event.detail);
      this.servicios = [];
      this.totalServicios = 0;
      this.pagination.page = 1;
      this.getServicios();
    },
    mostrarModalEliminar(item) {
      this.modalEliminar = true;
      this.item = item;
    },
    closeModal() {
      this.modalEliminar = false;
      this.item = "";
    },
    deleteArticulo() {
      this.modalEliminar = false;
      axios.get(`api/delete-servicio/${this.item.id}`).then(
        (res) => {
          $toast.sucs("Artículo eliminado");
          this.item = "";
          this.getServicios();
        },
        (err) => {
          $toast.error("Error eliminando artículo");
        }
      );
    },
    openFormArticulo(item) {
      this.selectedItemId = item ? item.id : null;
      this.isFormArticuloVisible = true;
    },
    closeFormArticulo() {
      this.isFormArticuloVisible = false;
      this.selectedItemId = null;
    },
    openLogPrecio(item) {
      this.logPrecioDescripcion = item.descripcion || `N.º ${item.nro ?? item.id}`;
      this.loadingLogPrecio = true;
      this.logPrecioItems = [];
      this.dialogPrecioLog = true;
      axios
        .get(`api/servicio-precio-cambios/${item.id}`)
        .then((res) => {
          const raw = Array.isArray(res.data) ? res.data : [];
          this.logPrecioItems = this.enriquecerVigenciasPrecioLog(raw);
        })
        .catch(() => {
          $toast.error("No se pudo cargar el historial de precios");
          this.dialogPrecioLog = false;
        })
        .finally(() => {
          this.loadingLogPrecio = false;
        });
    },
    /** Fecha calendario local en formato dd/mm/aaaa */
    fechaCalendarioDMY(iso) {
      const d = new Date(iso);
      if (Number.isNaN(d.getTime())) return "";
      const day = String(d.getDate()).padStart(2, "0");
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const year = d.getFullYear();
      return `${day}/${month}/${year}`;
    },
    /** Día calendario anterior al de `iso` (precio anterior “hasta” ese día). */
    diaAnteriorCalendarioDMY(iso) {
      const d = new Date(iso);
      if (Number.isNaN(d.getTime())) return "";
      d.setHours(12, 0, 0, 0);
      d.setDate(d.getDate() - 1);
      const day = String(d.getDate()).padStart(2, "0");
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const year = d.getFullYear();
      return `${day}/${month}/${year}`;
    },
    /**
     * API: más reciente primero. Precio nuevo vigente desde el día del cambio;
     * hasta el día anterior al siguiente cambio, o “sigue vigente” si es el último.
     */
    enriquecerVigenciasPrecioLog(raw) {
      return raw.map((r, i) => {
        const vigenciaAnteriorHasta = this.diaAnteriorCalendarioDMY(r.created_at);
        const vigenciaNuevoDesde = this.fechaCalendarioDMY(r.created_at);
        let vigenciaNuevoHasta = null;
        if (i > 0) {
          vigenciaNuevoHasta = this.diaAnteriorCalendarioDMY(raw[i - 1].created_at);
        }
        return {
          ...r,
          vigenciaAnteriorHasta,
          vigenciaNuevoDesde,
          vigenciaNuevoHasta,
        };
      });
    },
    formatFechaHoraPrecio(value) {
      if (!value) return "";
      const d = new Date(value);
      if (Number.isNaN(d.getTime())) return "";
      return d.toLocaleString("es-ES", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    },
    formatPriceServicios(value) {
      if (value == null || value === "" || isNaN(Number(value))) return "";
      const num = Number(value);
      const rounded = Math.round(num * 1000) / 1000;
      const hasThirdDecimal = rounded !== Math.round(rounded * 100) / 100;
      const str = hasThirdDecimal ? rounded.toFixed(3) : rounded.toFixed(2);
      const [intPart, decPart] = str.split(".");
      const intFormatted = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return `${intFormatted},${decPart}`;
    },
  },
  computed: {
    isloading: function () {
      return this.$store.getters.getloading;
    },
  },
};
</script>
