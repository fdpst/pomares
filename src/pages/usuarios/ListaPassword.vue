<template>
  <section>
    <div class="text-center">
      <loader v-if="isloading"></loader>
    </div>

    <VCard class="mb-6" title="Filtros">
      <VCardText>
        <div class="app-user-search-filter d-flex align-center">
           <!-- 👉 Search  -->
           <VTextField v-model="searchQuery" style="min-width: 200px" placeholder="Buscar" density="compact"
            class="me-4" />

            <VBtn @click="openDialogFormPassword = true" >Nuevo</VBtn>
        </div>
      </VCardText>
      
      <VDivider />

      <VDataTable dense :headers="headers" :items="items" :search="search" hide-default-footer item-key="id" class="elevation-2">
        <template v-slot:item.action="{item}">
          <VIcon @click="editarPassword(item)" small class="mr-2" color="grey-600">
            ri-shield-keyhole-line
          </VIcon>

          <VIcon @click="setPassword(item)" small class="mr-2" color="grey-600">
            ri-delete-bin-line
          </VIcon>
        </template>
      </VDataTable>
    </VCard>

    <check-password 
      v-model="openDialogCheckPassword"
      @get_passwords="getPassword"
    />

    <dialog-form-password 
      v-model="openDialogFormPassword"
      :passwordForm="passwordForm"
      @saved="pushItem"
    />

    <ConfirmDialog 
      :title="'Eliminar contraseña'" 
      v-model="openDialogConfirmPassword"
      @confirm="deletePassword"
      @cancel="openDialogConfirmPassword=false"
    />
  </section>
</template>

<script>
import checkPassword from './checkPassword.vue'
import dialogFormPassword from './dialogFormPassword.vue'
export default {
  components: {
    checkPassword,
    dialogFormPassword,
  },

  data() {
    return {
      search: '',
      headers: [{
          title: 'Cuenta',
          value: 'cuenta'
        },
        {
          title: 'Detalle',
          value: 'detalle'
        },
        {
          title: 'Fecha',
          value: 'created_at'
        },
        {
          title: 'Acciones',
          value: 'action',
          sortable: false
        },
      ],
      items: [],
      item_to_delete: {},

      openDialogFormPassword: false,
      passwordForm: {
        id: null,
        cuenta: null,
        password: null,
        real_password: null,
        detalle: null,
        observaciones: null
      },

      openDialogCheckPassword: false,

      openDialogConfirmPassword: false
    }
  },

  mounted() {
    this.openDialogCheckPassword = true
  },

  methods: {
    getPassword() {
      axios.get(`api/get-passwords`).then(res => {
        this.items = res.data
      }, err => {
        $toast.error('Error consultando Usuario')
      })
    },

    editarPassword(item) {
      axios.get(`api/get-password/${item.id}`).then(res => 
      {
        this.passwordForm = res.data
        this.openDialogFormPassoword = true

      }, err => {
        $toast.error('Error consultando Usuario')
      })
    },

    pushItem(item) {
      let index = this.items.findIndex(x => x.id == item.id);
      if(index > -1)
      {
        this.items[index] = item
      }
      else
      {
        this.items.unshift(item)
      } 
    },

    setPassword(item) {
      this.item_to_delete = item
      this.openDialogConfirmPassword = true
    },

    deletePassword() {
      axios.get(`api/delete-password/${this.item_to_delete.id}`).then(res => {
        this.items.splice(this.items.indexOf(this.item_to_delete), 1)
        $toast.sucs('Contraseña eliminada con exito')
      }, err => {
        $toast.error('Error consultando Usuario')
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
<style lang="scss">
.app-user-search-filter {
  inline-size: 24.0625rem;
}

.text-capitalize {
  text-transform: capitalize;
}

.user-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
}
</style>
