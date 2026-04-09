<template>
  
  <VProgressLinear
    :label="getProgress"
    height="23"
    :value="getProgress"
    
    rounded
    style="color:white!important"
  >
    {{ progress < 100 ? getProgress :  '¡Cargado con éxito!' }}
  </VProgressLinear>

</template>


<script >
  export default{
    props : {
      progress : {
        type : Number,
        default: 0,
      }
    },
    computed: {
      getProgress(){
        return Math.round(this.progress) + '%'
      }
    },
    data () {
      return {

        

      }
    },
   
    methods:{

     

    }
  }
</script>