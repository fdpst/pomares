import { effectiveBusinessUserId } from '@/utils/tenantContext'

export function sanitizePdfFilename(name, fallback = 'documento') {
  const base = String(name || fallback)
    .replace(/[^a-zA-Z0-9._-]+/g, '_')
    .replace(/^_+|_+$/g, '') || fallback

  return base.toLowerCase().endsWith('.pdf') ? base : `${base}.pdf`
}

/**
 * URL HTTP autenticada para abrir/descargar PDF en el visor del navegador.
 * Evita blob: (Chrome no puede guardar bien esos ficheros).
 */
export function buildAuthedPdfUrl(apiPath, extraParams = {}) {
  const base = (window.axios?.defaults?.baseURL || window.location.origin).replace(
    /\/$/,
    ''
  )
  const path = String(apiPath || '').replace(/^\//, '')
  const params = new URLSearchParams({
    _t: String(Date.now()),
    ...extraParams,
  })

  const token = localStorage.getItem('id_token')
  if (token) {
    params.set('pdf_token', token)
  }

  const userId = effectiveBusinessUserId()
  if (userId) {
    params.set('user_id', String(userId))
  }

  const role = parseInt(localStorage.getItem('role') || '0', 10)
  const selectedCliente = localStorage.getItem('selected_cliente_id')
  if ((role === 3 || role === 4) && selectedCliente) {
    params.set('cliente_id', selectedCliente)
  }

  return `${base}/${path}?${params.toString()}`
}

export function abrirPdfEnNuevaPestana(apiPath, extraParams = {}) {
  const url = buildAuthedPdfUrl(apiPath, extraParams)
  const win = window.open(url, '_blank', 'noopener,noreferrer')

  if (!win) {
    return { ok: false, popupBlocked: true, url }
  }

  return { ok: true, url }
}
