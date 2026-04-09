const modulo_paises = {
 	
 	strict: false,
	
	state() {
		return {
			paises:[] 
		}
	},

	getters: {
		get_paises: state => state.paises,
	},

	mutations: {
		set_paises: (state, lista_paises) => state.paises = lista_paises,
	},
	 
 	actions: {
		setPaises: (context, lista_paises) => context.commit('set_paises', lista_paises),
 	}

}

export default modulo_paises;