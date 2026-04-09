const AlbaranesEnviados = ()=> import('@/pages/albaranes/AlbaranesEnviados.vue')
const FormAlbaranesEnviados = ()=> import('@/pages/albaranes/FormAlbaranesEnviados.vue')
const FormEnviadosUpdate = ()=> import('@/pages/albaranes/FormEnviadosUpdate.vue')

const AlbaranesRecibidos = ()=> import('@/pages/albaranes/AlbaranesRecibidos.vue')
const FormAlbaranesRecibido = ()=> import('@/pages/albaranes/FormAlbaranesRecibido.vue')
const FormAlbaranesRecibidoUpdate = ()=> import('@/pages/albaranes/FormRecibidosUpdate.vue')


const routes = [

	{
		path: `/lista-albaranes-enviados`,
		name: 'lista-albaranes-enviados',
		component: AlbaranesEnviados,
		meta: {
			Auth: true
		}
	},

	{
		path: `/form-albaranes-enviados`,
		name: 'form-albaranes-enviados',
		component: FormAlbaranesEnviados,
		meta: {
			Auth: true
		}
	},

	{
		path: `/form-albaranes-enviados-update/:idAlbaran`,
		name: 'form-albaranes-enviados-update',
		component: FormEnviadosUpdate,
		meta: {
			Auth: true
		}
	},


	{
		path: `/lista-albaranes-recibidos`,
		name: 'lista-albaranes-recibidos',
		component: AlbaranesRecibidos,
		meta: {
			Auth: true
		}
	},

	{
		path: `/form-albaranes-recibidos`,
		name: 'form-albaranes-recibidos',
		component: FormAlbaranesRecibido,
		meta: {
			Auth: true
		}
	},

	{
		path: `/form-albaranes-recibidos-update/:idAlbaran`,
		name: 'form-albaranes-recibidos-update',
		component: FormAlbaranesRecibidoUpdate,
		meta: {
			Auth: true
		}
	},

]


export default routes
