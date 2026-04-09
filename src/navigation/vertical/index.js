const items = [
  {
    title: "Inicio",
    icon: { icon: "ri-home-line" },
    to: "inicio",
    user: [2, 1, 3],
  },

  {
    title: "Clientes",
    icon: { icon: "ri-group-2-fill" },
    to: "lista-clientes",
    user: [2, 3, 4],
  },

  {
    title: "Proveedores",
    icon: { icon: "ri-truck-line" },
    to: "lista-proveedores",
    user: [2, 3],
  },

  {
    title: "Albaranes",
    icon: { icon: "ri-file-chart-line" },
    user: [2, 3, 4],
    children: [
      {
        title: "Enviados",
        icon: { icon: "ri-file-chart-line" },
        to: "lista-notas",
        user: [2, 3, 4],
      },
      {
        title: "Recibidos",
        icon: { icon: "ri-file-chart-line" },
        to: "lista-albaranes-recibidos",
        user: [2, 3, 4],
      },
    ],
  },

  {
    title: "Presupuestos",
    icon: { icon: "ri-file-paper-2-line" },
    to: "lista-recibos",
    user: [2, 3],
  },

  {
    title: "Facturas",
    icon: { icon: "ri-bill-line" },
    user: [2, 3],
    children: [
      {
        title: "Facturas Enviadas",
        icon: { icon: "ri-bill-line" },
        to: "lista-facturas",
        user: [2, 3],
      },
      {
        title: "Facturas Recibidas",
        icon: { icon: "ri-bill-line" },
        to: "lista-facturas-recibidas",
        user: [2, 3],
      },
      {
        title: "Facturas Proforma",
        icon: { icon: "ri-bill-line" },
        to: "lista-facturas-proforma",
        user: [2, 3],
      },
      {
        title: "Facturas Rectificativas",
        icon: { icon: "ri-bill-line" },
        to: "lista-facturas-rectificativas",
        user: [2, 3],
      },
    ],
  },

  {
    title: "Exportar / Enviar Facturas",
    icon: { icon: "ri-mail-send-fill" },
    to: "enviar-facturas",
    user: [2, 3],
  },

  {
    title: "Contabilidad",
    icon: { icon: "ri-calculator-line" },
    user: [2, 3],
    children: [
      /*{
        title: 'Tablero',
        icon: { icon: 'ri-dashboard-2-line' },
        to: 'contabilidad',
        user: [2, 3],
      },
      {
        title: 'Exportar',
        icon: { icon: 'ri-calculator-line' },
        to: 'exportar-informe',
        user: [2, 3],
      },*/
      {
        title: "Ingresos",
        icon: { icon: "ri-calculator-line" },
        to: "lista-ingresos",
        user: [2, 3],
      },
      {
        title: "Gastos",
        icon: { icon: "ri-calculator-line" },
        to: "lista-gastos",
        user: [2, 3],
      },

      /*{
        title: 'Tipos Gastos',
        icon: { icon: 'ri-calculator-line' },
        to: 'tipos-gasto',
        user: [2],
      },
      {
        title: 'Libro diario',
        icon: { icon: 'ri-calculator-line' },
        to: 'lista-libro-diario',
        user: [2],
      },
      {
        title: 'Reporte IVA',
        icon: { icon: 'ri-calculator-line' },
        to: 'reporte-iva',
        user: [2],
      },*/
    ],
  },

  {
    title: "Pendiente de pago",
    icon: { icon: "ri-refund-line" },
    to: "morosos",
    user: [2, 3],
  },

  {
    title: "Usuarios",
    icon: { icon: "ri-group-2-fill" },
    to: "lista-usuario",
    user: [1],
  },
  {
    title: "Empresa",
    icon: { icon: "ri-user-line" },
    to: "lista-clientes-admin",
    user: [1],
  },

  {
    title: "Contraseñas",
    icon: { icon: "ri-key-fill" },
    to: "lista-passwords",
    user: [1],
  },

  {
    title: "Artículos de Venta",
    icon: { icon: "ri-article-line" },
    to: "lista-servicios",
    user: [2, 3],
  },

  {
    title: "Artículos Compra",
    icon: { icon: "ri-article-line" },
    to: "lista-productos",
    user: [2, 3],
  },

]

function get_items_user(user_role) {
  return items.filter(item => {
    return item.user.some(role => role == user_role)
  })
}

const user_role = localStorage.getItem("role")

const items_user = get_items_user(user_role)

export default items_user
