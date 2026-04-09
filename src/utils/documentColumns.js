// Configuración por defecto de columnas para documentos y formularios
export const DOCUMENT_COLUMN_DOC_TYPES = [
  "factura",
  "facturarectificativa",
  "facturaproforma",
  "presupuesto",
  "nota",
  "parte-trabajo",
  "form",
]

export const DOCUMENT_COLUMNS_DEFAULT = [
  {
    id: "cantidad",
    label: "Cantidad",
    field: "cantidad",
    align: "center",
    width: 12,
    format: "number",
    order: 10,
    docTypes: [
      "factura",
      "facturarectificativa",
      "facturaproforma",
      "presupuesto",
      "nota",
      "parte-trabajo",
      "form",
    ],
    enabled: true,
  },
  {
    id: "descripcion",
    label: "Descripción",
    field: "descripcion",
    align: "start",
    width: 40,
    format: "text",
    order: 20,
    docTypes: [
      "factura",
      "facturarectificativa",
      "facturaproforma",
      "presupuesto",
      "nota",
      "parte-trabajo",
      "form",
    ],
    enabled: true,
  },
  {
    id: "servicio",
    label: "Servicio/Artículo",
    field: "id_servicio",
    align: "start",
    width: 16,
    format: "text",
    order: 25,
    docTypes: ["form"],
    enabled: true,
  },
  {
    id: "precio",
    label: "Precio",
    field: "precio",
    align: "end",
    width: 12,
    format: "currency",
    order: 30,
    docTypes: [
      "factura",
      "facturarectificativa",
      "facturaproforma",
      "presupuesto",
      "nota",
      "parte-trabajo",
      "form",
    ],
    enabled: true,
  },
  {
    id: "iva",
    label: "IVA (%)",
    field: "iva_percent",
    align: "center",
    width: 12,
    format: "percent",
    order: 40,
    docTypes: [
      "factura",
      "facturarectificativa",
      "facturaproforma",
      "form",
    ],
    enabled: true,
  },
  {
    id: "importe",
    label: "Importe",
    field: "importe",
    align: "end",
    width: 12,
    format: "currency",
    order: 50,
    docTypes: [
      "factura",
      "facturarectificativa",
      "facturaproforma",
      "presupuesto",
      "nota",
      "parte-trabajo",
      "form",
    ],
    enabled: true,
  },
]

export const normalizeColumns = (columns = []) => {
  if (!Array.isArray(columns)) return DOCUMENT_COLUMNS_DEFAULT

  const normalized = columns
    .map((column, index) => {
      if (!column || typeof column !== "object") return null

      const docTypes = Array.isArray(column.docTypes)
        ? column.docTypes.filter(Boolean).map(type => String(type).toLowerCase())
        : []

      let field = column.field ?? column.id ?? ""
      
      // Si el field está vacío pero hay label, generar el field automáticamente
      if ((!field || field.trim() === "") && column.label) {
        field = column.label
          .toLowerCase()
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "") // Eliminar acentos
          .replace(/[^a-z0-9]/g, "_") // Reemplazar caracteres especiales con guión bajo
          .replace(/_+/g, "_") // Eliminar guiones bajos múltiples
          .replace(/^_|_$/g, "") // Eliminar guiones bajos al inicio y final
      }

      return {
        id: column.id ?? `col-${index}`,
        label: column.label ?? field ?? `Columna ${index + 1}`,
        field: field,
        align: column.align ?? "start",
        width: column.width ?? null,
        format: column.format ?? "text",
        order: column.order ?? index,
        docTypes: docTypes.length ? docTypes : ["form"],
        enabled: column.enabled !== undefined ? Boolean(column.enabled) : true,
      }
    })
    .filter(Boolean)

  return normalized.length ? normalized : DOCUMENT_COLUMNS_DEFAULT
}

export const filterColumnsByType = (columns, type) => {
  const normalized = normalizeColumns(columns)
  const currentType = String(type || "").toLowerCase()

  return normalized
    .filter(
      column =>
        column.enabled &&
        Array.isArray(column.docTypes) &&
        column.docTypes.includes(currentType),
    )
    .sort((a, b) => (a.order ?? 0) - (b.order ?? 0))
}

export const serializeColumns = columns =>
  JSON.stringify(normalizeColumns(columns))

