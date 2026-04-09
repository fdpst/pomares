<template>
  <div>
    <form-datos-empresa></form-datos-empresa>
  </div>
</template>
<script type="text/javascript">
  import FormDatosEmpresa from './FormDatosEmpresa.vue'
   export default {
    components:{
      'form-datos-empresa' : FormDatosEmpresa
    },
    data() {
      return {
         
        
      }
    },
    created() {
       
    },
    methods: {
      
    },
    computed: {
      isloading: function() {
        return this.$store.getters.getloading
      },
    }
  };
</script>