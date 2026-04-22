/**
 * Persistencia de filtros de fecha en listas (localStorage), por usuario efectivo.
 * Valores guardados como YYYY-MM-DD para compatibilidad con itemPasaFiltroFecha.
 */

const buildKey = (listaId, userId, campo) =>
  `pomares:filtro-fecha:${listaId}:u${String(userId || "0")}:${campo}`;

function toStoreValue(val) {
  if (val == null || val === "") {
    return "";
  }
  if (val instanceof Date) {
    if (Number.isNaN(val.getTime())) {
      return "";
    }
    return val.toISOString().slice(0, 10);
  }
  const s = String(val).trim();
  if (s === "" || s === "null" || s === "undefined") {
    return "";
  }
  const m = s.match(/^(\d{4}-\d{2}-\d{2})/);
  return m ? m[1] : "";
}

function fromStored(str) {
  if (str == null || str === "") {
    return null;
  }
  return String(str).trim();
}

/**
 * @param {'liquidaciones'|'facturas-recibidas'} listaId
 * @param {string|number|null|undefined} userId
 */
export function leerFiltroFechasLista(listaId, userId) {
  const u = String(userId || "0");
  const desde = fromStored(localStorage.getItem(buildKey(listaId, u, "desde")));
  const hasta = fromStored(localStorage.getItem(buildKey(listaId, u, "hasta")));
  return { desde, hasta };
}

export function escribirFiltroFechasLista(listaId, userId, desde, hasta) {
  const u = String(userId || "0");
  const kDesde = buildKey(listaId, u, "desde");
  const kHasta = buildKey(listaId, u, "hasta");
  const vDesde = toStoreValue(desde);
  const vHasta = toStoreValue(hasta);
  if (vDesde) {
    localStorage.setItem(kDesde, vDesde);
  } else {
    localStorage.removeItem(kDesde);
  }
  if (vHasta) {
    localStorage.setItem(kHasta, vHasta);
  } else {
    localStorage.removeItem(kHasta);
  }
}

export function borrarFiltroFechasLista(listaId, userId) {
  const u = String(userId || "0");
  localStorage.removeItem(buildKey(listaId, u, "desde"));
  localStorage.removeItem(buildKey(listaId, u, "hasta"));
}
