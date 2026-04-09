const modulo_provincias = {
	strict: false,

  	state() {
  		return {
  			provincias:[]
  		}
  	},	

	getters: {
		get_provincias: state => state.provincias,
	},

	mutations: {
		set_provincias: (state, lista_provincias) => state.provincias = lista_provincias,
	},

	actions: {
		setProvincias: (context, lista_provincias) => context.commit('set_provincias', lista_provincias),
	}
}

export default modulo_provincias;
