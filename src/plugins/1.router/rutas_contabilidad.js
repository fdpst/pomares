const Lote = ()=> import('@/pages/contabilidad/Lote.vue')
const Contabilidad = ()=> import('@/pages/contabilidad/Contabilidad.vue')
const ExportarInformes = ()=> import("@/pages/contabilidad/ExportarInformes.vue")
const ListaIngresos = ()=> import('@/pages/contabilidad/ingresos/ListaIngresos.vue')
const FormIngreso = ()=> import('@/pages/contabilidad/ingresos/FormIngreso.vue')
const ListaGastos = ()=> import('@/pages/contabilidad/gastos/ListaGastos.vue')
const FormGasto = ()=> import('@/pages/contabilidad/gastos/FormGasto.vue')
const FormUpdateGasto = ()=> import('@/pages/contabilidad/gastos/FormUpdateGasto.vue')
const TiposGasto = ()=> import('@/pages/contabilidad/gastos/TiposGasto.vue')
const ListaLibroDiario = ()=> import('@/pages/contabilidad/libro_diario/ListaLibroDiario.vue')
const FormLibroDiario = ()=> import('@/pages/contabilidad/libro_diario/FormLibroDiario.vue')
const ReportesIva = ()=> import('@/pages/contabilidad/reportes_iva/ReporteIva.vue')


const routes = [

	{
		path: '/enviar-facturas',
		name: 'enviar-facturas',
		component: Lote,
		meta: {
			Auth: true
		}
	},

	{
		path: '/contabilidad',
		name: 'contabilidad',
		component: Contabilidad,
		meta: {
			Auth: true
		}
	},

	{
		path: `/exportar-informe`,
		name: 'exportar-informe',
		component: ExportarInformes,
		meta: {
			Auth: true
		}
	},

	{
		path: `/lista-ingresos`,
		name: 'lista-ingresos',
		component: ListaIngresos,
		meta: {
			Auth: true
		}
	},

	{
		path: `/guardar-ingreso`,
		name: 'guardar-ingreso',
		component: FormIngreso,
		meta: {
			Auth: true
		}
	},

	{
		path: `/lista-gastos`,
		name: 'lista-gastos',
		component: ListaGastos,
		meta: {
			Auth: true
		}
	},

	{
		path: `/guardar-gasto`,
		name: 'guardar-gasto',
		component: FormGasto,
		meta: {
			Auth: true
		}
	},

	{
		path: `/update-gasto/:id`,
		name: 'update-gasto',
		component: FormUpdateGasto,
		meta: {
			Auth: true
		}
	},

	{
		path: `/tipos-gasto`,
		name: 'tipos-gasto',
		component: TiposGasto,
		meta: {
			Auth: true
		}
	},

	{
		path: '/lista-libro-diario',
		name: 'lista-libro-diario',
		component: ListaLibroDiario,
		meta: {
			Auth: true
		}
	},

	{
		path: '/guardar-libro-diario',
		name: 'guardar-libro-diario',
		component: FormLibroDiario,
		meta: {
			Auth: true
		}
	},

	{
		path: '/reporte-iva',
		name: 'reporte-iva',
		component: ReportesIva,
		meta: {
			Auth: true
		}
	}

]


export default routes
