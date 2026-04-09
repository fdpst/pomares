<template>
  <VRow>

    <VCol cols="12" :md="vertical ? 12 : 6">
      <VTextField
        filled
        v-model="cuenta.partida"
        name="partida"
        label="Partida"
      >
      </VTextField>
    </VCol>


    <VCol cols="12" :md="vertical ? 12 : 6">
      <div style="display: flex">
        <VTextField
          filled
          readonly
          v-model="cuenta.numero"
          name="cuenta"
          label="Cuenta contable asociada"
        >
        </VTextField>
        
        <VBtn class="ml-2" @click="dialog = true"
          ><VIcon>ri-file-search-fill</VIcon></VBtn
        >

        <VDialog v-model="dialog" width="900px">
          <CategoriaCuentaCard
            v-model="cuenta.id_categoria"
            @accept="selectCategory"
            @cancel="dialog = false"
          />
        </VDialog>
        
      </div>
    </VCol>


  </VRow>
</template>


<script>

import CategoriaCuentaCard from "./CategoriaCuentaCard.vue";

export default 
{

  components: {
    CategoriaCuentaCard,
  },

  props: ["modelValue", "nro", "nombre", "vertical"],

  data() {
    return {
      cuenta: {},
      dialog: false,
      categoria: null,
    };
  },

  created() {
    this.getValue();
  },

  watch: {

    cuenta: {
      deep: true,
      handler: function (val) {
        this.$emit("update:modelValue", val);
      },
    },

    modelValue: {
      deep: true,
      handler: function (val) {
        this.getValue();
      },
    },

  },

  methods: {

    selectCategory(categoria) 
    {
      if (categoria.length > 0) 
      {
        this.cuenta.id_categoria = categoria[0].id;
        this.cuenta.partida = categoria[0].denominacion + " - " + this.nombre;
        let nro = this.nro.toString();
        
        if (nro == null || nro == "") {
          nro = "XXXX";
        }

        this.cuenta.numero = categoria[0].cuenta.padEnd(9 - nro.length, "0") + nro;
      }
      this.dialog = false;
    },

    getValue() 
    {
      if (this.modelValue != null) {
        this.cuenta = this.modelValue;
      } else {
        this.cuenta = {};
      }
    },

  },
};
</script>
