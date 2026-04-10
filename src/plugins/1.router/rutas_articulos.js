const FormArticulos = ()=> import("@/pages/articulos/FormArticulos.vue")
// import ListaArticulos from "@/pages/articulos/ListaArticulos.vue";

const ListaArticulos = () => import('@/pages/articulos/ListaArticulos.vue')


const routes = [

	{
		path: '/guardar-servicio',
		name: 'guardar-servicio',
		component: FormArticulos,
		meta: {
			Auth: true,
			venta: 1,
			titulo:"Artículos (venta)",
			lista: `lista-servicios`
		}
	},

	{
		path: '/guardar-productos',
		name: 'guardar-productos',
		component: FormArticulos,
		meta: {
			Auth: true,
			venta: 0,
			titulo:"Artículos",
			lista: `lista-productos`
		}
	},

	{
		path: `/lista-servicios`,
		name: 'lista-servicios',
		component: ListaArticulos,
		meta: {
			Auth: true,
			venta: 1,
			titulo:"Artículos (venta)",
			form:"guardar-servicio"
		}
	},

	{
		path: `/lista-productos`,
		name: 'lista-productos',
		component: ListaArticulos,
		meta: {
			Auth: true,
			venta: 0,
			titulo:"Artículos",
			form:"guardar-productos"
		}
	}

];


export default routes;
