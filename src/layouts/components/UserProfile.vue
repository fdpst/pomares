<script>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

export default
{
  components: {
    PerfectScrollbar
  },
  
  props: {
    selectorOnly: {
      type: Boolean,
      default: false
    },
    userIconOnly: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      clientes: [],
      clienteSeleccionado: null,
      loadingClientes: false,
      clienteAnterior: null, // Guardar el valor anterior para comparar
    }
  },

  computed: {
    isGestor() {
      return parseInt(localStorage.getItem('role')) === 3
    },
    selectedClienteId() {
      return localStorage.getItem('selected_cliente_id')
    },
    showSelector() {
      // Solo mostrar selector si es gestor y no es solo icono de usuario
      return this.isGestor && !this.userIconOnly
    },
    showUserIcon() {
      // Mostrar icono si no es solo selector
      return !this.selectorOnly
    }
  },

  async mounted() {
    console.log('UserProfile mounted - isGestor:', this.isGestor, 'role:', localStorage.getItem('role'))
    // Solo cargar clientes si es gestor y se debe mostrar el selector
    if (this.isGestor && this.showSelector) {
      // Cargar cliente seleccionado si existe
      const clienteId = localStorage.getItem('selected_cliente_id')
      if (clienteId) {
        const clienteIdNum = parseInt(clienteId)
        this.clienteSeleccionado = clienteIdNum
        this.clienteAnterior = clienteIdNum // Guardar como valor anterior
      }
      
      // Cargar clientes asociados y seleccionar el primero si no hay uno seleccionado
      await this.loadClientesAsociados()
    }
  },

  methods: {
    async logout()
    {
      axios.post('/api/logout').then(response => 
      {
        localStorage.removeItem('id_token')
        localStorage.removeItem('user_name')
        localStorage.removeItem('user_email')
        localStorage.removeItem('role')
        localStorage.removeItem('user_id')
        localStorage.removeItem('selected_cliente_id')

        document.cookie = 'userData=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'
        document.cookie = 'accessToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'
        document.cookie = 'userAbilityRules=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'

        this.$router.push('/login')

      }).catch(error => {
        console.log(error)
      })
    },

    async loadClientesAsociados() {
      if (!this.isGestor || !this.showSelector) {
        console.log('No es gestor o no se debe mostrar selector, no se cargan clientes')
        return
      }
      
      console.log('Cargando clientes asociados...')
      this.loadingClientes = true
      try {
        const response = await axios.get('/api/gestor/clientes')
        console.log('Respuesta de clientes:', response.data)
        this.clientes = response.data.clientes || []
        console.log('Clientes cargados:', this.clientes)
        
        // NO seleccionar automáticamente el primer cliente
        // El gestor debe seleccionar manualmente un cliente
        if (this.selectedClienteId) {
          // Verificar que el cliente seleccionado sigue siendo válido
          const clienteExiste = this.clientes.find(c => c.id == this.selectedClienteId)
          if (!clienteExiste) {
            // Si el cliente seleccionado ya no es válido, limpiar la selección
            // NO seleccionar automáticamente otro cliente
            console.log('Cliente seleccionado no válido, limpiando selección')
            this.clienteSeleccionado = null
            this.clienteAnterior = null
            localStorage.removeItem('selected_cliente_id')
          } else {
            const clienteIdNum = parseInt(this.selectedClienteId)
            this.clienteSeleccionado = clienteIdNum
            this.clienteAnterior = clienteIdNum
          }
        }
      } catch (error) {
        console.error('Error cargando clientes asociados:', error)
        $toast.error('Error al cargar clientes asociados')
      } finally {
        this.loadingClientes = false
      }
    },

    async cambiarCliente(clienteId) {
      if (!this.isGestor) return

      // Convertir a número para comparación
      const nuevoClienteId = parseInt(clienteId)
      
      // Usar el valor anterior guardado (antes de que el v-model lo actualice)
      // Si no hay valor anterior, obtener del localStorage
      const valorAnterior = this.clienteAnterior || (localStorage.getItem('selected_cliente_id') ? parseInt(localStorage.getItem('selected_cliente_id')) : null)
      
      // Si es el mismo cliente, no hacer nada
      if (valorAnterior === nuevoClienteId) {
        console.log('UserProfile: Mismo cliente seleccionado, no hacer nada', { 
          valorAnterior, 
          nuevoClienteId
        });
        return
      }

      console.log('UserProfile: Cambiando cliente...', { 
        from: valorAnterior, 
        to: nuevoClienteId
      });
      
      // Guardar el valor actual como anterior para la próxima vez
      this.clienteAnterior = nuevoClienteId

      try {
        const response = await axios.post('/api/gestor/cambiar-contexto', {
          cliente_id: nuevoClienteId
        })

        console.log('UserProfile: Respuesta del servidor:', response.data);

        if (response.data.status === 200) {
          const oldClienteId = valorAnterior
          this.clienteSeleccionado = nuevoClienteId
          localStorage.setItem('selected_cliente_id', nuevoClienteId.toString())
          
          console.log('UserProfile: Cliente cambiado, emitiendo evento...', { nuevoClienteId, oldClienteId });
          
          // Emitir evento personalizado para que los componentes se actualicen
          // Esto permite que los componentes se actualicen sin recargar la página
          const event = new CustomEvent('cliente-selected-changed', {
            detail: { 
              clienteId: nuevoClienteId,
              oldClienteId: oldClienteId
            }
          });
          
          window.dispatchEvent(event);
          console.log('UserProfile: Evento cliente-selected-changed emitido exitosamente', { nuevoClienteId, oldClienteId });
        } else {
          console.warn('UserProfile: Respuesta del servidor no fue exitosa:', response.data);
        }
      } catch (error) {
        console.error('UserProfile: Error cambiando contexto:', error)
        $toast.error('Error al cambiar de cliente')
      }
    }
  }
}

</script>


<template>
  <div>
    <!-- Conmutador de clientes para gestores - solo si selectorOnly o es gestor -->
    <VSelect
      v-if="showSelector"
      v-model="clienteSeleccionado"
      :items="clientes"
      item-value="id"
      item-title="name"
      density="compact"
      variant="outlined"
      style="min-width: 200px; max-width: 250px;"
      :loading="loadingClientes"
      :disabled="clientes.length === 0"
      @update:model-value="cambiarCliente"
      hide-details
      :placeholder="clientes.length === 0 ? 'Sin clientes asociados' : 'Seleccionar cliente'"
      class="gestor-select"
    >
      <template #prepend-inner>
        <VIcon icon="ri-switch-line" size="18" class="me-2" />
      </template>
    </VSelect>

    <!-- Icono de usuario - solo si no es selectorOnly -->
    <VBadge
      v-if="showUserIcon"
      dot
      bordered
      location="bottom right"
      offset-x="3"
      offset-y="3"
      color="success"
    >
      <VAvatar
        class="cursor-pointer user-avatar"
        size="38"
        variant="flat"
      >

        <VIcon icon="ri-user-line" color="black" />

        <VMenu
          activator="parent"
          width="230"
          location="bottom end"
          offset="15px"
        >
          <VList class="user-menu-list">

            <PerfectScrollbar :options="{ wheelPropagation: false }">

              <VListItem
                link
                :to="{ name: 'datos-empresa' }"
              >
                <VListItemTitle>Datos de Empresa</VListItemTitle>
              </VListItem>

              <VListItem
                link
                :to="{ name: 'system-params' }"
              >
                <VListItemTitle>Configuración</VListItemTitle>
              </VListItem>

              <VListItem>
                <VBtn
                  block
                  color="error"
                  size="small"
                  append-icon="ri-logout-box-r-line"
                  @click="logout"
                >
                  Salir
                </VBtn>
              </VListItem>
            </PerfectScrollbar>
          </VList>
        </VMenu>

      </VAvatar>
    </VBadge>
  </div>
</template>

<style scoped>
.user-avatar {
  background-color: #f5f5f5;
}

.gestor-select :deep(.v-field__input) {
  color: #000 !important;
}

.gestor-select :deep(.v-select__selection) {
  color: #000 !important;
}

.gestor-select :deep(input) {
  color: #000 !important;
}
</style>
