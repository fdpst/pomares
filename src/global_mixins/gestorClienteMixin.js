/**
 * Mixin para escuchar cambios en el cliente seleccionado cuando el usuario es gestor
 * Usar este mixin en componentes que necesiten recargar datos cuando cambie el cliente
 */
export default {
  created() {
    console.log('gestorClienteMixin: created() ejecutado');
    // Guardar referencia al componente para usar en los listeners
    const self = this;
    
    // Escuchar cambios en el cliente seleccionado
    // Usar arrow function para mantener el contexto correcto
    this._clienteChangedHandler = (event) => {
      console.log('gestorClienteMixin: Evento recibido en listener', event);
      self.handleClienteChanged(event);
    };
    
    this._storageChangedHandler = (event) => {
      self.handleStorageChange(event);
    };
    
    window.addEventListener('cliente-selected-changed', this._clienteChangedHandler);
    console.log('gestorClienteMixin: Listener de cliente-selected-changed registrado');
    
    // También escuchar cambios en localStorage (para otros casos)
    window.addEventListener('storage', this._storageChangedHandler);
  },
  
  mounted() {
    console.log('gestorClienteMixin: mounted() ejecutado');
    // Verificar que el método onClienteChanged existe
    if (this.onClienteChanged && typeof this.onClienteChanged === 'function') {
      console.log('gestorClienteMixin: onClienteChanged está disponible');
    } else {
      console.warn('gestorClienteMixin: onClienteChanged NO está disponible. El componente debe implementar este método.');
    }
  },
  
  beforeUnmount() {
    // Limpiar listeners usando las referencias guardadas
    if (this._clienteChangedHandler) {
      window.removeEventListener('cliente-selected-changed', this._clienteChangedHandler);
    }
    if (this._storageChangedHandler) {
      window.removeEventListener('storage', this._storageChangedHandler);
    }
  },
  
  methods: {
    handleClienteChanged(event) {
      console.log('gestorClienteMixin: handleClienteChanged llamado', event);
      // Este método debe ser sobrescrito en el componente que use el mixin
      // para definir qué hacer cuando cambia el cliente
      if (this.onClienteChanged && typeof this.onClienteChanged === 'function') {
        console.log('gestorClienteMixin: Llamando a onClienteChanged del componente');
        this.onClienteChanged(event);
      } else {
        console.warn('gestorClienteMixin: onClienteChanged no está disponible en el componente');
      }
    },
    
    handleStorageChange(event) {
      // Si cambia selected_cliente_id en localStorage, recargar datos
      // Nota: Este evento solo se dispara cuando el cambio viene de otra pestaña/ventana
      // Para cambios en la misma pestaña, usamos el evento personalizado
      if (event.key === 'selected_cliente_id') {
        const role = parseInt(localStorage.getItem('role'));
        if (role === 3) {
          console.log('selected_cliente_id cambió en localStorage (otra pestaña), recargando datos...');
          if (this.onClienteChanged && typeof this.onClienteChanged === 'function') {
            this.onClienteChanged(event);
          }
        }
      }
    }
  }
}

