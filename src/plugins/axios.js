import axios from 'axios'

window.axios = axios

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
axios.defaults.withCredentials = true;

// Raíz de la app Laravel (evita que con URL .../form-facturas-recibidas-update/10 las rutas
// tipo "api/foo" se resuelvan como .../form-facturas-recibidas-update/api/foo → 404).
const viteBase = (import.meta.env.VITE_API_BASE_URL || '').replace(/\/$/, '')
const metaApp = document.querySelector('meta[name="app-url"]')?.getAttribute('content')?.trim().replace(/\/$/, '') || ''
const originFallback =
  typeof window !== 'undefined' && window.location?.origin ? window.location.origin : ''
axios.defaults.baseURL = viteBase || metaApp || originFallback

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

    const role = parseInt(localStorage.getItem('role') || '0', 10)
    const selectedClienteId = localStorage.getItem('selected_cliente_id')

    // Gestor (3) o empleado (4) con cliente seleccionado: contexto de cuenta de negocio
    // (misma lógica que GestorHelper::getUserId + validación en servidor).
    if ((role === 3 || role === 4) && selectedClienteId) {
      config.headers['X-Selected-Cliente-Id'] = selectedClienteId

      const method = (config.method || 'get').toLowerCase()

      // GET, DELETE: cliente_id en query para GestorHelper
      if (method === 'get' || method === 'delete') {
        config.params = config.params || {}
        if (config.params.cliente_id === undefined || config.params.cliente_id === null) {
          config.params.cliente_id = selectedClienteId
        }
      }

      // PUT/PATCH: query (Laravel Request::query en algunos controladores)
      if (method === 'put' || method === 'patch') {
        config.params = config.params || {}
        if (config.params.cliente_id === undefined || config.params.cliente_id === null) {
          config.params.cliente_id = selectedClienteId
        }
      }

      // POST/PUT/PATCH con FormData: el helper GestorHelper lee cliente_id del body;
      // sin esto, rutas como update-usuario no resolvían al cliente y fallaba el guardado.
      if (
        method !== 'get' &&
        method !== 'delete' &&
        config.data instanceof FormData &&
        !config.data.has('cliente_id')
      ) {
        config.data.append('cliente_id', selectedClienteId)
      }

      // POST/PUT/PATCH JSON (objeto plano): merge cliente_id para GestorHelper::input()
      if (
        (method === 'post' || method === 'put' || method === 'patch') &&
        config.data &&
        typeof config.data === 'object' &&
        !(config.data instanceof FormData) &&
        !Array.isArray(config.data)
      ) {
        if (config.data.cliente_id === undefined || config.data.cliente_id === null) {
          config.data = { ...config.data, cliente_id: selectedClienteId }
        }
      }
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
