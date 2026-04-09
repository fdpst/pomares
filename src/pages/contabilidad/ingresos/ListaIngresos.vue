<template>
  <VCard class="pb-10" title="Lista Ingresos">

    <div class="ps-5 pe-5 pb-5">
      
      <VRow>
        <VCol cols="12" md="8">
          <VTextField prepend-icon="ri-user-search-fill" v-model="search" label="Búsqueda"></VTextField>
        </VCol>

        <VCol cols="12" md="4" class="text-end">
          <VBtn rounded depressed color="primary" class="mt-1" :to="`/guardar-ingreso`">Nuevo</VBtn>
        </VCol>
      </VRow>

    </div>

    
    <loader v-if="isloading"></loader>


    <!--<rango-fechas :url="url" v-on:success_query="setIngresos"></rango-fechas>-->

    <VDataTable :headers="headers" :items="ingresos" :search="search" disable-pagination hide-default-footer item-key="id" class="elevation-1 mt-2">

      <template v-slot:item.created_at="{ item }">
        {{format_date_filter(item.created_at)}}
      </template>

      <template v-slot:item.importe="{ item }">
        {{ formatPrice(item.importe) }}€
      </template>

      <template v-slot:item.action="{ item }">
        <RouterLink :to="`/guardar-ingreso?id=${item.id}`" class="action-buttons">
          <VIcon small class="mr-2" color="grey-600">
            ri-pencil-line
          </VIcon>
        </RouterLink>

        <VIcon @click="mostrarModalEliminar(item)" small class="mr-2" color="red">
          ri-delete-bin-line
        </VIcon>

      </template>

    </VDataTable>

  </VCard>


  <ConfirmDialog
    v-model="modalEliminar"
    @cancel="closeModal"
    @confirm="deleteIngreso"
  />

</template>

<script>
import { date_mixin } from '../mixins/date_mixin'
import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js'
import rangoFechas from '../rangoFechas.vue'
import { formatPrice } from '@/@core/utils/formatters'

export default {
  mixins: [date_mixin, gestorClienteMixin],

    components: {
      rangoFechas
    },

    data() {
      return {
        modalEliminar: false,
        item:'',
        url: `api/get-ingresos`,
        search: '',
        ingresos: [],
        headers: [{
            title: 'Codigo',
            align: 'left',
            value: 'codigo',
          },
          {
            title: 'Fecha',
            value: 'created_at',
          },
          {
            title: 'Importe',
            value: 'importe',
          },
          {
            title: 'Acciones',
            value: 'action',
            sortable: false,
          },
        ],
      }
    },
    created() {
      this.getIngresos()
    },
    methods: {
      getIngresos() {
      axios.get(`api/get-ingresos`).then(res => {
          this.ingresos = res.data
        }, err => {
          $toast.error('Error consultando clientes')
        })
      },
      setIngresos(data) {
        if (data.length > 0) {
          this.ingresos = data
          return
        }
        $toast.sucs('No se encontraron registros')
      },
      mostrarModalEliminar(item){
        this.modalEliminar = true;
        this.item = item;
      },
      closeModal(){
        this.modalEliminar = false;
        this.item = '';
      },
      deleteIngreso() {
        this.modalEliminar = false;
        axios.get(`api/delete-ingreso/${this.item.id}`).then(res => {
          this.ingresos.splice(this.ingresos.indexOf(this.item), 1);
          $toast.sucs('Ingreso eliminado con exito')
          this.item= '';
        }, err => {
          $toast.error('Error Eliminando ingreso')
        })
    },
    onClienteChanged(event) {
      console.log('ListaIngresos: Cliente cambiado, recargando ingresos...', event.detail)
      this.ingresos = []
      this.getIngresos()
      }
    },
    formatPrice,
    computed: {
      isloading: function() {
        return this.$store.getters.getloading
    },
    effectiveUserId() {
      const role = parseInt(localStorage.getItem('role'))
      const selectedCliente = localStorage.getItem('selected_cliente_id')
      if (role === 3 && selectedCliente) {
        return selectedCliente
      }
      return localStorage.getItem('user_id')
      }
    }
  }
</script>
