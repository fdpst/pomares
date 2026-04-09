const FormProveedor = ()=> import('@/pages/proveedores/FormProveedor.vue')
const ListaProveedores = ()=> import('@/pages/proveedores/ListaProveedores.vue')

const routes = [
	{
		path: '/guardar-proveedor',
		name: 'guardar-proveedor',
		component: FormProveedor,
		meta: {
			Auth: true
		}
	},

	{
		path: '/lista-proveedores',
		name: 'lista-proveedores',
		component: ListaProveedores,
		meta: {
			Auth: true
		}
	},
]


export default routes
