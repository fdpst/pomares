import axios from 'axios'

window.axios = axios

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
axios.defaults.headers.common.Authorization = `Bearer ${localStorage.getItem('id_token')}`
axios.defaults.withCredentials = true;

// Configuración para producción
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || ''

// Interceptor para agregar cliente_id cuando el usuario es gestor
axios.interceptors.request.use(
  config => {
    const role = parseInt(localStorage.getItem('role'))
    const selectedClienteId = localStorage.getItem('selected_cliente_id')
    
    // Si es gestor y tiene un cliente seleccionado, agregar el cliente_id
    if (role === 3 && selectedClienteId) {
      // Agregar como header
      config.headers['X-Selected-Cliente-Id'] = selectedClienteId
      
      // También agregar como parámetro en la URL si es GET
      if (config.method === 'get') {
        config.params = config.params || {}
        if (config.params.cliente_id === undefined || config.params.cliente_id === null) {
          config.params.cliente_id = selectedClienteId
        }
      }
      
      // Para POST/PUT/PATCH, NO agregar automáticamente el cliente_id
      // El cliente_id debe ser seleccionado explícitamente por el usuario
      // Solo agregar si ya existe en el body y no es null/undefined
      // Esto permite que la validación del backend funcione correctamente
    }
    
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// Interceptor para manejar errores de autenticación
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      // Redirigir al login o manejar el error de autenticación
      console.error('Error de autenticación:', error.response.data);
    }
    return Promise.reject(error);
  }
);
