<template>

  <VRow>

    <VCol md="10" cols="12">

      <VCard title="Tipos de Gasto">

        <VDivider></VDivider>

        
        <ConfirmDialog
          v-model="modalEliminar"
          @cancel="closeModal"
          @confirm="deleteTiposGasto"
        />


        <VContainer>

          <VRow class="mt-1">
            <VCol cols="12" md="6">
              <VTextField v-model="tipo_gasto.nombre" label="Nombre" required></VTextField>
            </VCol>

            <VCol cols="12" md="6">
              <VBtn rounded depressed color="info" class="mt-1 white--text" @click="saveTiposGasto()">Añadir</VBtn>
            </VCol>
          </VRow>

          <loader v-if="isloading"></loader>

          <VRow>
            <VCol cols="12" md="6">
              <VTextField prepend-icon="ri-user-search-fill" v-model="search" label="Codigo / Nombre"></VTextField>
            </VCol>
          </VRow>

          <VDataTable :headers="headers" :items="tipos_gasto" :search="search" item-key="id" class="elevation-1 mt-10 mb-5">

            <template v-slot:item.action="{ item }">
              <VIcon @click="setTiposGasto(item)" small class="mr-2" color="blue">
                ri-pencil-line
              </VIcon>
              <VIcon @click="mostrarModalEliminar(item)" small class="mr-2" color="red">
                ri-delete-bin-line
              </VIcon>
            </template>
          </VDataTable>

        </VContainer>

      </VCard>

    </VCol>

  </VRow>

</template>

<script>
  export default {
    data() {
      return {
        modalEliminar: false,
        item: '',
        search: '',
        tipo_gasto: {
          id: '',
          nombre: '',
          user_id: localStorage.getItem('user_id')
        },
        tipos_gasto: [],
        headers: [{
            title: 'Código',
            align: 'left',
            value: 'id',
          },
          {
            title: 'Nombre',
            value: 'nombre',
            filterable: false,
          },
          {
            title: 'Acciones',
            value: 'action',
            sortable: false,
          },
        ],
      }
    },
    mounted() {
      this.getTiposGasto()
    },
    methods: {

      getTiposGasto() {
        axios.get(`api/get-tipos-gasto/${localStorage.getItem('user_id')}`).then(res => {
          this.tipos_gasto = res.data

        }, res => {
          $toast.error('Error consultando tipos de gasto')
        })
      },
      saveTiposGasto() {
        axios.post('api/save-tipos-gasto', this.tipo_gasto).then(res => {
          this.tipo_gasto = {
            id: '',
            nombre: ''
          }
          this.getTiposGasto()
        }, res => {
          if (res.response.status == 301) {
            return $toast.warn(res.response.data.mensaje)
          }
          $toast.error('Error Guardando tipo de gasto')
        })
      },
      setTiposGasto(item) {
        this.tipo_gasto = JSON.parse(JSON.stringify(item))
      },
      mostrarModalEliminar(item) {
        this.modalEliminar = true;
        this.item = item;
      },
      closeModal() {
        this.modalEliminar = false;
        this.item = '';
      },
      deleteTiposGasto(item) {
        this.modalEliminar = false;
        axios.get(`api/delete-tipos-gasto/${this.item.id}`).then(res => {
          this.tipos_gasto.splice(this.tipos_gasto.indexOf(this.item), 1);
          $toast.sucs('Gasto eliminado con exito')
          this.item = '';
        }, err => {
          $toast.error('Error eliminando tipo de gasto')
        })
      },
    },
    computed: {
      isloading: function() {
        return this.$store.getters.getloading
      },
    }

  }
</script>