import { isToday } from './helpers'

export const avatarText = value => {
  if (!value)
    return ''
  const nameArray = value.split(' ')
  
  return nameArray.map(word => word.charAt(0).toUpperCase()).join('')
}

// TODO: Try to implement this: https://twitter.com/fireship_dev/status/1565424801216311297
export const kFormatter = num => {
  const regex = /\B(?=(\d{3})+(?!\d))/g
  
  return Math.abs(num) > 9999 ? `${Math.sign(num) * +((Math.abs(num) / 1000).toFixed(1))}k` : Math.abs(num).toFixed(0).replace(regex, ',')
}

/**
 * Format and return date in Humanize format
 * Intl docs: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/DateTimeFormat/format
 * Intl Constructor: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/DateTimeFormat/DateTimeFormat
 * @param {string} value date to format
 * @param {Intl.DateTimeFormatOptions} formatting Intl object to format with
 */
export const formatDate = (value, formatting = { month: 'short', day: 'numeric', year: 'numeric' }) => {
  if (!value)
    return value
  
  return new Intl.DateTimeFormat('es-ES', formatting).format(new Date(value))
}

export const formatDateEs = (value) => {
  if (!value)
    return value
  
  try {
    // Asegurarnos de que tenemos una fecha válida
    const date = new Date(value);
    if (isNaN(date.getTime())) return ""; // Fecha inválida
    
    // Obtener los componentes de la fecha
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    
    return `${day}-${month}-${year}`;
  } catch (error) {
    console.error('Error formateando fecha:', error);
    return "";
  }
}

/**
 * Return short human friendly month representation of date
 * Can also convert date to only time if date is of today (Better UX)
 * @param {string} value date to format
 * @param {boolean} toTimeForCurrentDay Shall convert to time if day is today/current
 */
export const formatDateToMonthShort = (value, toTimeForCurrentDay = true) => {
  const date = new Date(value)
  let formatting = { month: 'short', day: 'numeric' }
  if (toTimeForCurrentDay && isToday(date))
    formatting = { hour: 'numeric', minute: 'numeric' }
  
  return new Intl.DateTimeFormat('es-ES', formatting).format(new Date(value))
}

/** Número de decimales a mostrar: 3 solo si el valor tiene tercer decimal significativo. */
function priceDecimalPlaces(value) {
  const n = Number(value);
  if (Number.isNaN(n)) return 2;
  return Math.round(n * 1000) === Math.round(n * 100) * 10 ? 2 : 3;
}

export const formatPrice = (value) => {
  if (value === '' || value === null || isNaN(value)) return '';
  const n = Number(value);
  const decimals = priceDecimalPlaces(n);
  return n
    .toFixed(decimals)
    .replace('.', ',')
    .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

export const inputPrice = (value) => {
  // Permitir números, puntos y comas
  let formattedValue = value.target._value.replace(/[^0-9.,-]/g, ""); 
  return formattedValue;
}
export const parseEuroNumber = (value) => {
  if(value === '' || value === null) return 0;

  if(typeof value === 'string') {
    if(value.includes(',')) {
      return parseFloat(value.replace(/\./g, '').replace(',', '.'));
    }
    return parseFloat(value);
  }

  return parseFloat(value);
}

export const prefixWithPlus = value => value > 0 ? `+${value}` : value
