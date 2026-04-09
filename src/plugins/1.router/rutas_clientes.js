const FormCliente = ()=> import('@/pages/clientes/FormCliente.vue')
const ListaClientes = ()=> import('@/pages/clientes/ListaClientes.vue')

const routes = [
   
   {
		path: '/lista-clientes',
		name: 'lista-clientes',
		component: ListaClientes,
		meta: {
			Auth: true
		}
	},

	{
		path: '/guardar-cliente',
		name: 'guardar-cliente',
		component: FormCliente,
		meta: {
			Auth: true
		}
	},

]


export default routes
