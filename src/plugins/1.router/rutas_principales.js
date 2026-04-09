const Inicio = ()=> import("@/pages/Inicio.vue")
const Login = ()=> import("@/pages/Login.vue")
const ForgotPassword = ()=> import("@/pages/ForgotPassword.vue")

const Test = ()=> import("@/pages/Test.vue")


const routes = [

	{
		path: '/',
		name: 'inicio',
		component: Inicio,
		meta: {
			Auth: true
		}
	},

	{
		path: '/recuperar-contrasena',
		name: 'recuperar-contrasena',
		component: ForgotPassword,
		meta: {
			layout: 'blank',
	   	unauthenticatedOnly: true
   	}
	},

	{
		path: '/login',
		name: 'login',
		component: Login,
		meta: {
			layout: 'blank',
			unauthenticatedOnly: true
   	}
	},

	{
		path: '/test',
		name: 'test',
		component: Test,
		meta: {
			layout: 'blank',
   	}
	},

]


export default routes
