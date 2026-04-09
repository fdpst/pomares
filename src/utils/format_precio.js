/**
 * Devuelve el número de decimales a mostrar (2 o 3).
 * Solo 3 si el valor tiene tercer decimal significativo.
 */
function getDecimalPlaces(num) {
  const n = Number(num);
  if (Number.isNaN(n)) return 2;
  return Math.round(n * 1000) === Math.round(n * 100) * 10 ? 2 : 3;
}

export const format_precio = (numero) => {
  const float_numero = parseFloat(numero);
  if (float_numero == null || Number.isNaN(float_numero)) return "0,00€";
  const decimals = getDecimalPlaces(float_numero);
  return `${float_numero.toFixed(decimals).replace(".", ",")}€`;
};
