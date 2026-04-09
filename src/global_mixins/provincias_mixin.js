export const provincias_mixin = {
  created(){
    if(this.provincias.length == 0){
      this.getProvincias();
      // START cambios para cientes con Pais
      this.getPaises();
      // END cambios para cientes con Pais
    }
  },

  methods:{
    getProvincias(){
      axios.get(`/api/get-provincias`).then(res => {
          this.$store.dispatch('setProvincias', res.data)
      }, res => {
         this.$toast.error('Error cargando provincias')
      })
    },    
    
    // START cambios para cientes con Pais
    getPaises(){
      axios.get(`/api/get-paises`).then(res => {
        console.log(res)
        this.$store.dispatch('setPaises', res.data)
      }, res => {
          this.$toast.error('Error cargando paises')
      })
    },
    // END cambios para cientes con Pais
  },

  computed: {
    provincias() {
      return this.$store.getters.get_provincias
    // START cambios para cientes con Pais
    },
    paises() {
      return this.$store.getters.get_paises
    },
    provincias_filter: function() {
      if (this.id_pais != 0 && this.cliente.pais_id !=0 ) {
        return this.provincias.filter(x => x.id_pais == this.cliente.pais_id
      )}
    // END cambios para cientes con Pais
    }
  }
}
