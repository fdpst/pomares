<template>
  
  <v-date-input 
    :label="label"
    v-model="fecha"
    variant="outlined"
    first-day-of-week="1"
    cancel-text="Cancelar"
    ok-text="OK"
    color="primary"
    @update:modelValue.sync="returnDate($event)"
  ></v-date-input>

</template>


<script>

export default {

  props: ["label", "value"],

  data() {
    return {
      fecha: null
    }
  },

  watch: {
    value(val) {
      this.fecha = val
    }
  },

  methods: {

    formatDate(date) 
    {
      let anio = date.getFullYear()
      let month = (date.getMonth() + 1).toString().padStart(2, '0')
      let day = date.getDate().toString().padStart(2, '0')

      return anio + '-' + month + '-' + day
    },

    returnDate(event) {
      this.$emit('input', this.formatDate(event))
    }

  }
}

</script>
