<template>
  <!-- <VRow> -->
    <VDialog 
      v-model="dialog" 
      max-width="600" 
      transition="dialog-bottom-transition" 
      persistent
      content-class="dialog-scrollable"
    > 
      <VCard>
        <VCardTitle class="dialog-add-albaranes__title">
          Seleccione albarán para asociar a factura
        </VCardTitle>

        <VCardText class="overflow-y-auto" style="max-height: 60vh;">
          <VRow>
            <VList
              lines="three"
              density="compact"
              select-strategy="classic"
            >
              <VListItem v-for="albaran,index in albaranesEnviados" :key="index" :value="albaran.nro_factura">
                <template #prepend="{ isActive }">
                  <VListItemAction>
                    <VCheckbox
                      :model-value="isActive"
                      color="primary"
                      @change="valueChanged($event, albaran)"
                    />
                  </VListItemAction>
                </template>
                <VListItemSubtitle>
                  <strong><VIcon color="green">ri-file-line</VIcon> ALBARAN : </strong> {{albaran.nro_factura}}
                </VListItemSubtitle> 
                <VListItemSubtitle>                  
                  <strong><VIcon color="green">ri-account-circle-fill</VIcon> CLIENTE : </strong> {{albaran.cliente.nombre}}
                </VListItemSubtitle>
                <VListItemSubtitle>                  
                  <strong><VIcon>ri-money-euro-circle-line</VIcon> IMPORTE : </strong> {{albaran.importe}} € 
                </VListItemSubtitle>                 
                <VListItemSubtitle>                  
                  <VIcon color="red">ri-file-line</VIcon><strong> PDF </strong>                     
                  <a target="_blank" :href="'/storage/albaranes/enviados/' +albaran.url">
                  <VIcon color="blue">ri-eye-line</VIcon> Ver </a>
                </VListItemSubtitle> 
              </VListItem>
            </VList>
          </VRow>
        </VCardText>
        <VCardActions class="justify-center pb-4">
          <VBtn color="secondary" class="btn-cancel-dialog" @click="closeModalAlbaranes(checkbox)">Cancelar selección</VBtn>
          <VBtn color="#5142A6" class="btn-confirm-dialog text-white" @click="closeModalAlbaranesListo">Aceptar</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  <!-- </VRow> -->
</template>

<script>
  export default {
    props: {
      modalAddAlbaranes: Boolean,
      closeModalAlbaranes: Function,
      albaranesEnviados: Array,
      closeModalAlbaranesListo: Function,
      addAlbaranAlaLista: Function,
      deleteItem: Function,
      obtenerServicio: Function,
      checkbox: Array
    },
    data () {
      return {
        dialog: false,
        notifications: false,
        sound: true,
        widgets: false,      
      }
    },

    watch: {
      modalAddAlbaranes(val) {
        this.dialog = val
      }
    },

    methods:{
      valueChanged(event, albaran) {
        // Comprobamos si esta marcado el checkbox
        if (event === null || event.length === 0)
        { 
          let servicio = this.obtenerServicio(albaran.nro_factura)
          this.deleteItem(servicio,albaran.nro_factura)
        }
        else
        {
          this.addAlbaranAlaLista(albaran)
        }
      },
    }
  }
</script>
