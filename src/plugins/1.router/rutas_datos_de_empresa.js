const DatosEmpresa = ()=> import('@/pages/datos_de_empresa/DatosEmpresa.vue')

const routes = [

	{
		path: `/datos-empresa`,	
		name: 'datos-empresa',
		component: DatosEmpresa,
		meta: {
			Auth: true
		}
	}
 
]


export default routes
