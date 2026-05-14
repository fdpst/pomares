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

const buildBusquedaKey = (listaId, userId) =>
  `pomares:filtro-busqueda:${listaId}:u${String(userId || "0")}`;

/**
 * Texto de búsqueda guardado para la lista (mismo ámbito por usuario que las fechas).
 *
 * @param {string} listaId
 * @param {string|number|null|undefined} userId
 * @returns {string}
 */
export function leerFiltroBusquedaLista(listaId, userId) {
  const raw = localStorage.getItem(buildBusquedaKey(listaId, String(userId || "0")));
  if (raw == null) {
    return "";
  }
  return String(raw);
}

/**
 * @param {string} listaId
 * @param {string|number|null|undefined} userId
 * @param {string|null|undefined} search
 */
export function escribirFiltroBusquedaLista(listaId, userId, search) {
  const key = buildBusquedaKey(listaId, String(userId || "0"));
  const s = search == null ? "" : String(search).trim();
  if (s === "") {
    localStorage.removeItem(key);
  } else {
    localStorage.setItem(key, s);
  }
}

export function borrarFiltroBusquedaLista(listaId, userId) {
  localStorage.removeItem(buildBusquedaKey(listaId, String(userId || "0")));
}

const buildMostrarFacturadasKey = (listaId, userId) =>
  `pomares:filtro-mostrar-facturadas:${listaId}:u${String(userId || "0")}`;

/**
 * @param {string} listaId
 * @param {string|number|null|undefined} userId
 * @returns {boolean}
 */
export function leerMostrarFacturadasLista(listaId, userId) {
  const raw = localStorage.getItem(
    buildMostrarFacturadasKey(listaId, String(userId || "0"))
  );
  return raw === "1" || raw === "true";
}

/**
 * @param {string} listaId
 * @param {string|number|null|undefined} userId
 * @param {boolean} mostrar
 */
export function escribirMostrarFacturadasLista(listaId, userId, mostrar) {
  const key = buildMostrarFacturadasKey(listaId, String(userId || "0"));
  if (mostrar) {
    localStorage.setItem(key, "1");
  } else {
    localStorage.removeItem(key);
  }
}

export function borrarMostrarFacturadasLista(listaId, userId) {
  localStorage.removeItem(
    buildMostrarFacturadasKey(listaId, String(userId || "0"))
  );
}
