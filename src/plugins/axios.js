import axios from 'axios'

window.axios = axios

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
axios.defaults.withCredentials = true;

// Raíz de la app Laravel (evita que con URL .../lista-proveedores/ las rutas tipo "api/foo"
// se resuelvan como .../lista-proveedores/api/foo).
const viteBase = (import.meta.env.VITE_API_BASE_URL || '').replace(/\/$/, '')
const metaApp = document.querySelector('meta[name="app-url"]')?.getAttribute('content')?.trim().replace(/\/$/, '') || ''
axios.defaults.baseURL = viteBase || metaApp || ''

// Interceptor para agregar cliente_id cuando el usuario es gestor
axios.interceptors.request.use(
  config => {
    // FormData: no fijar Content-Type (axios/navegador añaden multipart + boundary).
    // Si queda application/json por defecto o multipart sin boundary, Laravel no recibe los campos.
    if (config.data instanceof FormData) {
      if (typeof config.headers.delete === 'function') {
        config.headers.delete('Content-Type')
      } else {
        delete config.headers['Content-Type']
      }
    }

    const token = localStorage.getItem('id_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    } else {
      delete config.headers.Authorization
    }

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
