/**
 * Filtro por rango de fechas opcional (desde / hasta) para listas.
 * Cualquiera de los dos puede ir solo; si ambos faltan, no se filtra.
 */

export function normalizarFechaItem(fecha) {
    if (fecha == null || fecha === "") {
        return null;
    }
    return String(fecha).substring(0, 10);
}

export function normalizarValorFiltroFecha(val) {
    if (val == null || val === "" || val === undefined) {
        return null;
    }
    if (val instanceof Date && !Number.isNaN(val.getTime())) {
        const y = val.getFullYear();
        const m = String(val.getMonth() + 1).padStart(2, "0");
        const d = String(val.getDate()).padStart(2, "0");
        return `${y}-${m}-${d}`;
    }
    const s = String(val);
    return s.length >= 10 ? s.substring(0, 10) : s;
}

/**
 * @param {string|null|undefined} fechaItem Fecha del registro (ISO o YYYY-MM-DD)
 * @param {unknown} fechaDesde Límite inferior opcional
 * @param {unknown} fechaHasta Límite superior opcional
 * @returns {boolean}
 */
export function itemPasaFiltroFecha(fechaItem, fechaDesde, fechaHasta) {
    const d = normalizarFechaItem(fechaItem);
    if (!d) {
        return true;
    }
    const desde = normalizarValorFiltroFecha(fechaDesde);
    const hasta = normalizarValorFiltroFecha(fechaHasta);
    if (desde && d < desde) {
        return false;
    }
    if (hasta && d > hasta) {
        return false;
    }
    return true;
}
