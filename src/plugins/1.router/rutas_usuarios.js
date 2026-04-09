const FormUsuarios = ()=> import('@/pages/usuarios/FormUsuarios.vue')
const ListaUsuarios = ()=> import('@/pages/usuarios/ListaUsuarios.vue')
const ListaClientesAdmin = ()=> import('@/pages/usuarios/ListaClientesAdmin.vue')
const ListaPassword = ()=> import('@/pages/usuarios/ListaPassword.vue')

const routes = [

	{
		path: '/lista-usuario',
		name: 'lista-usuario',
		component: ListaUsuarios,
		meta: {
			Auth: true,
			req_admin: true
		}
	},

	{
		path: '/lista-clientes-admin',
		name: 'lista-clientes-admin',
		component: ListaClientesAdmin,
		meta: {
			Auth: true,
			req_admin: true
		}
	},

	{
		path: '/guardar-usuario',
		name: 'guardar-usuario',
		component: FormUsuarios,
		meta: {
			Auth: true,
			req_admin: true
		}
	},

	{
		path: '/lista-passwords',
		name: 'lista-passwords',
		component: ListaPassword,
		meta: {
			Auth: true,
			req_admin: true
		}
	},

]


export default routes
