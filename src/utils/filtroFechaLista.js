/**
 * Convierte un valor de fecha a entero YYYYMMDD (solo calendario, sin hora).
 * Acepta ISO `YYYY-MM-DD`, `YYYY-MM-DDTHH:mm:ss`, objetos Date o cadenas parseables.
 *
 * @param {string|Date|null|undefined} val
 * @returns {number|null}
 */
function aYYYYMMDD(val) {
  if (val == null || val === "") {
    return null;
  }
  if (val instanceof Date) {
    if (Number.isNaN(val.getTime())) {
      return null;
    }
    return (
      val.getFullYear() * 10000 +
      (val.getMonth() + 1) * 100 +
      val.getDate()
    );
  }
  const s = String(val).trim();
  const m = s.match(/^(\d{4})-(\d{2})-(\d{2})/);
  if (m) {
    const y = parseInt(m[1], 10);
    const mo = parseInt(m[2], 10);
    const d = parseInt(m[3], 10);
    if (mo < 1 || mo > 12 || d < 1 || d > 31) {
      return null;
    }
    return y * 10000 + mo * 100 + d;
  }
  const parsed = new Date(s);
  if (Number.isNaN(parsed.getTime())) {
    return null;
  }
  return (
    parsed.getFullYear() * 10000 +
    (parsed.getMonth() + 1) * 100 +
    parsed.getDate()
  );
}

/**
 * Indica si la fecha del ítem cae dentro del rango [desde, hasta] (inclusive).
 * Si ambos extremos son null/undefined/vacío, no hay filtro (siempre true).
 *
 * @param {string|Date|null|undefined} fechaItem
 * @param {string|Date|null|undefined} desde
 * @param {string|Date|null|undefined} hasta
 * @returns {boolean}
 */
export function itemPasaFiltroFecha(fechaItem, desde, hasta) {
  const sinDesde =
    desde == null || desde === "" || desde === "null" || desde === "undefined";
  const sinHasta =
    hasta == null || hasta === "" || hasta === "null" || hasta === "undefined";

  if (sinDesde && sinHasta) {
    return true;
  }

  if (
    fechaItem == null ||
    fechaItem === "" ||
    fechaItem === "null" ||
    fechaItem === "undefined"
  ) {
    return false;
  }

  const nItem = aYYYYMMDD(fechaItem);
  if (nItem == null) {
    return false;
  }

  if (!sinDesde) {
    const nDesde = aYYYYMMDD(desde);
    if (nDesde != null && nItem < nDesde) {
      return false;
    }
  }

  if (!sinHasta) {
    const nHasta = aYYYYMMDD(hasta);
    if (nHasta != null && nItem > nHasta) {
      return false;
    }
  }

  return true;
}
