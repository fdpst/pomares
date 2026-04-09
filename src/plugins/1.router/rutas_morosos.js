const ListaMorosos = ()=> import('@/pages/morosos/ListaMorosos.vue')

const routes = [

	{
		path: `/morosos`,
		name: 'morosos',
		component: ListaMorosos,
		meta: {
			Auth: true
		}
	},

]


export default routes
