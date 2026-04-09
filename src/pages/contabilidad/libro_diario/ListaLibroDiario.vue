<template>

  <VCard class="pa-4 pb-16">


    <VToolbar flat color="#1d2735" dark class="ps-4">
      <VIcon class="white--text" style="font-size: 45px"
        >mdi mdi-account-supervisor-circle</VIcon
      >
      <VToolbarTitle>
        <h3 style="overflow-wrap: normal;color: white;">
          Lista Libro Diario
        </h3></VToolbarTitle
      >
    </VToolbar>


    <VTooltip location="end">
      <template v-slot:activator="{ props }">
        <VBtn
          fab
          to="/guardar-libro-diario"
          :loading="isloading"
          :disabled="isloading"
          color="warning"
          class="mt-2"
          v-bind="props"
        >
          <VIcon class="white--text"
            >mdi mdi-account-plus-outline</VIcon
          >
        </VBtn>
      </template>
      <span>Nuevo Asiento</span>
    </VTooltip>


    <VRow align="end">
      <VCol align="end" cols="12">
        <VBtn
          v-if="selected.length > 0"
          @click="EliminarSeleccionados"
          :disabled="isloading"
          color="error"
          class="white--text"
          >Eliminar</VBtn
        >
      </VCol>
    </VRow>


    <!--Filtros-->
    <VRow>
      <VCol cols="12" md="8">
        <FilterComponentVue
          :headers="filter_headers"
          v-model="filtros_prueba"
        ></FilterComponentVue>
      </VCol>

      <VCol cols="12" md="4">
        <VBtn
          color="primary"
          class="white--text me-2"
          @click="getDatos()"
        >
          Filtrar
        </VBtn>
        <VBtn
          color="secondary"
          class="white--text"
          @click="
            filtros = {};
            filtros_prueba = {};
            getDatos();
          "
        >
          Limpiar Filtros
        </VBtn>

      </VCol>
    </VRow>


    <VDataTable
      @click:row="handleClick"
      dense
      v-model="selected"
      show-select
      :loading="loading"
      :headers="headers"
      :items="datos"
      :search="search"
      :items-per-page="100"
      item-key="id"
      :options.sync="options"
      class="elevation-1"
      :sort-by="['nombre']"
      :sort-desc="[false]"
      @update:options="onOptionsUpdate"
      :footer-props="{
        'items-per-page-options': [100, 500],
      }"
      :server-items-length="total"
      @update:items-per-page="ChangeSize"
      @pagination="ChangePage"
    >
  
      <template v-slot:item.action="{ item }">
        <a @click.stop="openModal(item)">
          <VIcon
            style="pointer-events: none; font-size: 25px"
            small
            class="mr-2"
            color="grey-600"
            title="BORRAR"
            >ri-delete-bin-line</VIcon
          >
        </a>
      </template>
    </VDataTable>

    
    <ConfirmDialog
      v-model="modalEliminar"
      @cancel="
        dialog = false;
        selectedItem = {};
      "
      @confirm="deleteAsientoLinea"
    />


    <VDialog v-model="dialog_comercial" max-width="1000px">
      <VCard>
        <VCardTitle class="text-h5 bg-warning text-center pa-4`">
          Crear Cliente
        </VCardTitle>
        <VCardText style="text-align: center">
          <VRow>
            <VCol cols="12" md="4">
              <VTextField
                v-model="cliente.razon_social"
                label="Razón social"
                required
              ></VTextField>
            </VCol>
            <VCol cols="12" md="4">
              <VTextField
                v-model="cliente.nombre_comercial"
                label="Nombre Comercial"
                required
              ></VTextField>
            </VCol>
            <VCol cols="12" md="4">
              <VTextField
                v-model="cliente.cif"
                label="CIF"
                required
              ></VTextField>
            </VCol>
          </VRow>
        </VCardText>
        <VCardActions class="pt-3">
          <VSpacer></VSpacer>

          <VBtn color="success" large @click="checkCliente()"
            >Confirmar</VBtn
          >
          <VSpacer></VSpacer>
        </VCardActions>
      </VCard>
    </VDialog>

  </VCard>
</template>


<script>

import FilterComponentVue from "@/components/FilterComponent.vue";

export default {
  data() {
    return {
      // agentes: [],
      filtros_prueba: {},
      filter_headers: [
        {
          title: "Fecha",
          type: "date",
          active: false,
          model: "fecha",
        },
        // {
        //     title: "Agente",
        //     type: "select",
        //     active: false,
        //     model: "agente",
        //     item_text: "nombre",
        //     item_value: "id",
        // },
        // {
        //     title: "Estado",
        //     type: "select",
        //     active: false,
        //     model: "estado",
        //     item_text: "nombre",
        //     item_value: "id",
        // },
      ],
      // agentes: [],
      options: {},
      sorted: true,
      show_busqueda: false,
      filtros: {
        validacion: 0,
      },
      all_selected: false,
      selected: [],
      search: "",
      
      loading: false,
      busqueda: "buscar",
      cantidad: 100,
      total: -1,
      // headers_agente: [
      //     {
      //         text: "#",
      //         value: "id",
      //         sortable: true,
      //     },
      //     {
      //         text: "Nombre",
      //         value: "nombre_comercial",
      //         sortable: false,
      //     },
      //     { text: "Fecha Alta", value: "created_at", sortable: false },
      //     { text: "Estado", value: "estado.nombre", sortable: false },
      //     { text: "CIF/NIF", value: "cif", sortable: false },

      //     {
      //         text: "Producto interesado",
      //         value: "interes",
      //         sortable: false,
      //     },
      //     { text: "Acciones", value: "action", sortable: false },
      // ],
       
      datos: [],
      headers: [
        { title: "Asiento", value: "apunte_contable_id", sortable: true, },
        { title: "Linea", value: "linea", sortable: false, },
        { title: "Fecha", value: "fecha", sortable: false },
        { title: "Tipo", value: "tipo", sortable: false },
        { title: "Descripcion", value: "descripcion", sortable: false },
        { title: "Documento", value: "documento", sortable: false, },
        { title: "Cuenta contable", value: "numero_cuenta", sortable: false },
        { title: "Nombre cuenta contable", value: "nombre_cuenta", sortable: false, },
        { title: "Debe", value: "debe", sortable: false, },
        { title: "Haber", value: "haber", },
        { title: "Acciones", value: "action", sortable: false },
      ],
      page: 1,
      rowsPerPage: 10,

      headers_mobile: [
        {
          title: "#",
          value: "id",
          sortable: true,
        },
        {
          title: "Nombre",
          value: "nombre_comercial",
          sortable: false,
        },
        { title: "Estado", value: "estado.nombre", sortable: false },
        {
          title: "Producto interesado",
          value: "interesado.nombre",
          sortable: false,
        },
        { title: "Acciones", value: "action", sortable: false },
      ],
      cliente: {
        cif: null,
        razon_social: "",
        nombre_comercial: "",
      },
 
      selectedItem: 0,
      dialog_comercial: false,
      dialog: false,
      rol: 0,
      isMobile: false,

      savedSortBy: null,
      savedSortDesc: null,
    };
  },
  mounted() {
    this.checkMobileView();
    // Listen for window resize events
    window.addEventListener("resize", this.checkMobileView);
  },
  beforeDestroy() {
    // Remove the resize event listener when the component is destroyed
    window.removeEventListener("resize", this.checkMobileView);
  },

  created() {
    // this.getEstadosPotencial();
    this.rol = localStorage.getItem("role");
    // this.getAgentes();

    this.getDatos();
  },
  watch: {
    filtros_prueba: {
      deep: true,
      handler: function (val) {
        //console.log(val);
      },
    },
    "$route.meta.rol": function (val) {
      this.getDatos();
    },
    "filtros.validacion": function (val) {
      this.getDatos();
    },
    search: debounce(function (val) {
      this.getDatos();
    }, 500),
  },
  methods: {
    // Get apuntes contables
    getDatos() {
      this.loading = true;
      let filtros = this.filterString;

      // let filtro_agentes =
      //     this.filtros_prueba.id_agente != null ? `&agente=${this.filtros.id_agente}` : "";

      if (this.filtros.fecha_desde) {
        filtros += "&fecha_desde=" + this.filtros.fecha_desde;
      }
      if (this.filtros.fecha_hasta) {
        filtros += "&fecha_hasta=" + this.filtros.fecha_hasta;
      }
      axios
        .get(
          `api/get-apuntes?page=${this.page}&rowsPerPage=${this.rowsPerPage}${filtros}`
        )
        .then(
          (res) => {
            if(res.data.code == 200){
              this.datos = res.data.success.data
              this.total = res.data.success.total
              this.loading = false;

              const sortBy = this.savedSortBy;
              const sortDesc = this.savedSortDesc;

              this.options = {
                ...this.options,
                sortBy: sortBy,
                sortDesc: sortDesc,
              };
            }else{
              $toast.error("Error consultando Apuntes contables")
            }
          },
          (err) => {
            $toast.error(err.response.data.error);
            this.loading = false;
          }
        );
    },


    // ExportExcel() {
    //     let filtros = this.filterString;

    //     axios
    //         .get(
    //             `/api/excel-cliente?rol=${this.$route.meta.rol}${filtros}`,
    //             { responseType: "blob" }
    //         )
    //         .then((response) => {
    //             const downloadUrl = window.URL.createObjectURL(
    //                 new Blob([response.data])
    //             );
    //             const link = document.createElement("a");
    //             link.href = downloadUrl;
    //             link.setAttribute("download", "clientes.xlsx");
    //             document.body.appendChild(link);
    //             link.click();
    //             link.remove();
    //         })
    //         .catch((error) => {
    //             console.error("Error exporting clientes:", error);
    //         });
    // },
    checkMobileView() {
      this.isMobile = window.innerWidth <= 768; // Set the breakpoint value as per your needs
    },
    onOptionsUpdate(options) {
      if (options.sortBy.length > 0) {
        this.savedSortBy = options.sortBy;
        this.savedSortDesc = options.sortDesc;
        //console.log(this.savedSortDesc);
        this.getDatos();
        // Execute your custom fun
      }
    },
    handleClick(value) {
      this.$router.push(`guardar-libro-diario?id=${value.id}`);
      //console.log(value);
    },
    obtenerConocido(item) {
      let nombre = "";
      this.conocido.forEach((element) => {
        if (element.id == item.id) {
          nombre = element.nombre;
        }
      });
      return nombre;
    },
    obtenerInteres(item) {
      let nombre = "";
      this.productos.forEach((element) => {
        if (element.id == item.id) {
          nombre = element.nombre;
        }
      });
      return nombre;
    },
    showBusqueda() {
      this.show_busqueda = true;
    },

    
    getRowClass(item) {
      if (item.agentes.length > 0)
        if (item.agentes[0].agente_relation != null)
          if (item.agentes[0].agente_relation.estadistica == 0) {
            return "yellow";
          }

      return "pointer";
    },
    downloadFile(response) {
      let blob = new Blob([response.data], { type: "xlsx" }),
        downloadUrl = window.URL.createObjectURL(blob),
        filename = "",
        disposition = response.headers["content-disposition"];

      if (disposition && disposition.indexOf("attachment") !== -1) {
        let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/,
          matches = filenameRegex.exec(disposition);

        if (matches != null && matches[1]) {
          filename = matches[1].replace(/['"]/g, "");
        }
      }

      let a = document.createElement("a");
      if (typeof a.download === "undefined") {
        window.location.href = downloadUrl;
      } else {
        a.href = downloadUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
      }
    },
    exportAll() {
      axios
        .get("api/export-clientes", {
          responseType: "blob",
        })
        .then((response) => {
          this.downloadFile(response);
        })
        .catch(error);
    },
    exportSelected() {
      let ids = [];
      this.selected.forEach((elemento) => {
        ids.push(elemento.id);
      });
      axios
        .post(
          "api/export-clientes",
          { ids: ids },
          {
            responseType: "blob",
          }
        )
        .then((response) => {
          this.downloadFile(response);
        })
        .catch(error);
    },
    exportFiltered() {
      let ids = [];

      axios
        .post(
          "api/export-clientes-filtered",
          { agente: this.filtros.id_agente },
          {
            responseType: "blob",
          }
        )
        .then((response) => {
          this.downloadFile(response);
        })
        .catch(error);
    },
    EliminarSeleccionados() {
      let ids = [];
      this.selected.forEach((elemento) => {
        ids.push(elemento.id);
      });
      axios.post(`api/delete-clientes`, { ids: ids }).then(
        (res) => {
          this.getDatos();
          this.selected = [];
          $toast.sucs("Clientes Eliminados");
        },
        (res) => {
          $toast.error("Error eliminando Clientes");
        }
      );
    },
    ChangeSize(event) {
      this.cantidad = event;
      this.getDatos();
    },
    ChangePage(event) {
      this.page = event.page;
      this.getDatos();
    },
    
    checkCliente() {
      axios.post(`api/check-cliente-comercial`, this.cliente).then(
        (res) => {
          //console.log(res.data);
          if (res.data.status) {
            this.$router.push(
              `${this.formpath}?rs=${this.cliente.razon_social}&cif=${this.cliente.cif}&nombre=${this.cliente.nombre_comercial}`
            );
          } else {
            $toast.error(res.data.msg);
          }
        },
        (res) => {
          $toast.error("Error consultando Clientes");
        }
      );
    },
    openModal(item) {
      this.selectedItem = this.datos.indexOf(item);
      this.dialog = true;
    },
    openModalComercial(item) {
      this.dialog_comercial = true;
    },
    deleteAsientoLinea() {
      let id = this.datos[this.selectedItem].id
      axios
        .delete(`api/delete-asiento-linea/${id}`)
        .then(
          (res) => {
            $toast.sucs("Clientes eliminado");
            this.dialog = false;
            this.getDatos();
          },
          (err) => {
            $toast.error("Error eliminando Clientes");
          }
        );
    },

    // FUNCIONES EN DESUSO
    getEstadosPotencial() {
      axios.get(`api/get-estados-potencial`).then(
        (res) => {
          this.filter_headers[2].items = res.data;
        },
        (res) => {}
      );
    },
    getAgentes() {
      axios.get(`api/get-agentes`).then(
        (res) => {
          this.filter_headers[1].items = [
            ...[{ id: 0, nombre: "Sin Asignar" }],
            ...res.data.Agentes,
          ];
          this.agentes = [
            ...[{ id: 0, nombre: "Sin Asignar" }],
            ...res.data.Agentes,
          ];
        },
        (res) => {}
      );
    },
  },
  filters: {},
  components: {
    // BusquedaCliente,
    FilterComponentVue,
  },
  computed: {
    filterString() {
      let filtros = "";
      if (
        this.filtros_prueba.search != null &&
        this.filtros_prueba.search != ""
      ) {
        filtros += `&busqueda=${this.filtros_prueba.search}`;
      }
      this.filter_headers.forEach((head) => {
        if (this.filtros_prueba[head.model]) {
          if (head.type == "date") {
            if (
              this.filtros_prueba[head.model].start != "" &&
              this.filtros_prueba[head.model].start != null
            ) {
              filtros += `&${head.model}_desde=${
                this.filtros_prueba[head.model].start
              }`;
            }
            if (
              this.filtros_prueba[head.model].end != "" &&
              this.filtros_prueba[head.model].end != null
            ) {
              filtros += `&${head.model}_hasta=${
                this.filtros_prueba[head.model].end
              }`;
            }
          } 
          else {
            if (
              (this.filtros_prueba[head.model].value != "" &&
                this.filtros_prueba[head.model].value !=
                  null) ||
              (this.filtros_prueba[head.model].nombre != "" &&
                this.filtros_prueba[head.model].nombre != null)
            ) {
              filtros += `&${head.model}=${
                this.filtros_prueba[head.model].value
              }`;
            }
          }
        }
      });
      return filtros;
    },
    formpath: function () {
      return `guardar-${
        this.$route.meta.rol == 1 ? "potencial" : "cliente"
      }`;
    },
    isloading: function () {
      // return this.$store.getters.getloading
    },
  },
};
</script>
