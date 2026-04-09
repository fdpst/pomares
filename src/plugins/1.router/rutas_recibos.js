const FormRecibo = ()=> import('@/pages/recibos/FormRecibo.vue')
const ListaRecibos = ()=> import('@/pages/recibos/ListaRecibos.vue')
const ListaFacturas = ()=> import('@/pages/recibos/ListaFacturas.vue')
const ListaFacturasRecibidas = ()=> import('@/pages/recibos/ListaFacturasRecibidas.vue')
const FormFacturasRecibidas = ()=> import('@/pages/recibos/FormFacturasRecibidas.vue')
const FormFacturasRecibidasUpdate = ()=> import('@/pages/recibos/FormFacturasRecibidasUpdate.vue')
const ListaFacturasProforma = ()=> import('@/pages/recibos/ListaFacturasProforma.vue')
const ListaNotas = () => import('@/pages/recibos/ListaNotas.vue');
const ListaAlbaranesEnviados = () => import('@/pages/albaranes/AlbaranesEnviados.vue');


const routes = [

	{
		path: '/guardar-recibo',
		name: 'guardar-recibo',
		component: FormRecibo,
		meta: {
			Auth: true
		}
	},    
	
	{
		path: `/lista-recibos`,
		name: 'lista-recibos',
		component: ListaRecibos,
		meta: {
			Auth: true
		}
	},

	{
		path: `/lista-facturas`,
		name: 'lista-facturas',
		component: ListaFacturas,
		meta: {
			Auth: true,
			tipo: 'factura'
		}
	},

	{
		path: `/lista-facturas-recibidas`,
		name: 'lista-facturas-recibidas',
		component: ListaFacturasRecibidas,
		meta: {
			Auth: true,
		}
	},

	{
		path: `/form-facturas-recibidas`,
		name: 'form-facturas-recibidas',
		component: FormFacturasRecibidas,
		meta: {
			Auth: true,
		}
	},

	{
		path: `/form-facturas-recibidas-update/:idFacturaRec`,
		name: 'form-facturas-recibidas-update',
		component: FormFacturasRecibidasUpdate,
		meta: {
			Auth: true,
		}
	},

	{
		path: `/lista-facturas-rectificativas`,
		name: 'lista-facturas-rectificativas',
		component: ListaFacturas,
		meta: {
			Auth: true,
			tipo: "facturarectificativa"
		}
	},

	{
		path: `/lista-facturas-proforma`,
		name: 'lista-facturas-proforma',
		component: ListaFacturasProforma,
		meta: {
			Auth: true,
		}
	},
	{
		path:`/lista-notas`,
		name: 'lista-notas',
		component: ListaNotas,
		meta: {
			Auth: true
		}
	}

]


export default routes
