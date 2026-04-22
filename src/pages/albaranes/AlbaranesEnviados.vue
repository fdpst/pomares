<template>
  <VCard class="pb-10" title="Lista de albaranes enviados">
    <div class="ps-5 pe-5 pb-5">
      <ConfirmDialog
        v-model="modalEliminar"
        @cancel="closeModal"
        @confirm="deleteAlbaran"
      />
      
      <loader v-if="isloading"></loader>

      <VRow>
        <VCol cols="12" md="8">
          <VTextField prepend-icon="ri-user-search-fill" v-model="search" label="Búsqueda"></VTextField>
        </VCol>

        <VCol cols="12" md="4" class="text-end">
          <VBtn rounded depressed color="primary" class="mt-1 white--text" :to="{ name: 'form-albaranes-enviados' }">Nuevo</VBtn>
        </VCol>
      </VRow>
    </div>

    <VDataTable :headers="headers" :items="albaranesEnviados" :search="search"  item-key="id" class="elevation-1 mt-3">
      <template v-slot:item.importe="{ item }">
        {{ formatPrice(item.importe) }}
      </template>
      <template v-slot:item.url="{ item }">
        <a target="_blank" :href="'/storage/albaranes/enviados/userId_'+userId+'/' +item.url">
          <VIcon medium color="orange" class="mr-2">
            ri-download-cloud-fill
          </VIcon>
        </a>
      </template>

      <template v-slot:item.action="{ item }">

        <RouterLink  :to="{ name: 'form-albaranes-enviados-update', params: { idAlbaran: item.id } }">
          <VIcon small class="mr-2" color="grey-600">
            ri-pencil-line
          </VIcon>
        </RouterLink>

        <VIcon @click="mostrarModalEliminar(item)" small class="mr-2" color="gray-600">
          ri-delete-bin-line
        </VIcon>
      </template>
    </VDataTable>
  </VCard>
</template>

<script>
  import { effectiveBusinessUserId } from '@/utils/tenantContext'

  export default {
    data() {
      return {
        modalEliminar: false,
        item:'',
        search: '',
        albaranesEnviados: [],
        headers: [
          // {
          //     text: 'Id',
          //     align: 'left',
          //     value: 'id',
          // }, 
          {
            title: 'Nro Albaran',
            value: 'nro_factura',
          },
          {
            title: 'Cliente',
            value: 'cliente.nombre'
          },
           
          {
            title: 'Total',
            value: 'importe',
          },
          {
            title: 'PDF',
            value: 'url'
          },
          {
            title: 'Contabilizado',
            value: 'contabilizado'
          },
          {
            title: 'Acciones',
            value: 'action',
            sortable: false
          },
        ],
      }
    },

    created() {
      this.getAlbaranes();
      
      // Escuchar evento para recargar cuando se guarda un albarán
      window.addEventListener('albaran-guardado', this.getAlbaranes);
    },
    
    beforeUnmount() {
      // Limpiar listener
      window.removeEventListener('albaran-guardado', this.getAlbaranes);
    },

    methods: {
      getAlbaranes() {
        axios.get(`api/get-albaranes-enviados`).then(res => {
          this.albaranesEnviados = res.data.enviados
        }, err => {
          $toast.error('Error consultando albaranes')
        })
      },
      mostrarModalEliminar(item){
        this.modalEliminar = true;
        this.item = item;
      },
      closeModal(){
        this.modalEliminar = false;
        this.item = '';
      },
      deleteAlbaran() {
        this.modalEliminar = false;
        axios.post(`api/delete-albaran-enviados/${this.item.id}`).then(res => {
          this.albaranesEnviados.splice(this.albaranesEnviados.indexOf(this.item), 1)
          $toast.sucs('Albaran eliminado')
          this.item= '';
        }, err => {
          $toast.error('Error eliminando albaran')
        })
      }
    },
    computed: {
      isloading: function() {
        return this.$store.getters.getloading
      },
      tenantDirId() {
        return effectiveBusinessUserId()
      },
    }
  }
</script>
