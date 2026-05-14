/**
 * Número de liquidación / autofactura en serie CO-N (N entero).
 * En formularios se muestra solo N; en API y BD se guarda "CO-N".
 */

export function nroCoToSoloNumero(val) {
  if (val == null || val === "" || val === "null" || val === "undefined") {
    return "";
  }
  const s = String(val).trim();
  const m = s.match(/^CO-(\d+)$/i);
  if (m) {
    return m[1];
  }
  return s.replace(/^CO-/i, "").trim();
}

/**
 * @param {string|number|null|undefined} val texto del campo (solo dígitos o vacío)
 * @returns {string} "CO-N", cadena vacía si no hay número, o el valor recortado si no hay dígitos
 */
export function soloNumeroANroCo(val) {
  const s = String(val ?? "").trim();
  if (s === "") {
    return "";
  }
  const digits = s.replace(/\D/g, "");
  if (digits === "") {
    return "";
  }
  return "CO-" + String(parseInt(digits, 10));
}
