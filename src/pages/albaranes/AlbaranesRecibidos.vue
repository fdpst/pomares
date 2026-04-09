<template>
  <VCard class="pb-10" title="Lista de albaranes recibidos">

    <ConfirmDialog
      v-model="modalEliminar"
      @cancel="closeModal"
      @confirm="deleteAlbaran"
    />

    <loader v-if="isloading"></loader>

    <div class="ps-5 pe-5 pb-5">
      <VRow>
        <VCol cols="12" md="8">
          <VTextField prepend-icon="ri-user-search-fill" v-model="search" label="Búsqueda"></VTextField>
        </VCol>

        <VCol cols="12" md="4" class="text-end">
          <VBtn rounded depressed color="primary" class="mt-1" :to="{ name: 'form-albaranes-recibidos' }">Nuevo</VBtn>
        </VCol>
      </VRow>
    </div>

    <VDataTable :headers="headers" :items="albaranes" :search="search" item-key="id" class="elevation-1 mt-1">
      <template v-slot:item.pdf="{ item }">
          <VIcon @click="callDown(item)" medium color="#5142A6" class="mr-2">
            ri-download-cloud-fill
          </VIcon>
      </template> 



      <template v-slot:item.action="{ item }">

        <RouterLink :to="{ name: 'form-albaranes-recibidos-update', params: { idAlbaran: item.id } }" class="action-buttons">
          <VIcon small class="mr-2" color="grey-600">
            ri-pencil-line
          </VIcon>
        </RouterLink>

        <VIcon @click="mostrarModalEliminar(item)" small class="mr-2" color="grey-600">
          ri-delete-bin-line
        </VIcon>

      </template>

    </VDataTable>

  </VCard>

</template>

<script>
  export default {
    data() {
      return {
        modalEliminar: false,
        item:'',
        search: '',
        albaranes: [],
        headers: [{
            title: 'Fecha',
            value: 'fecha',
          },
          {
            title: 'Proveedor',
            value: 'proveedor'
          },
          {
            title: 'Descripción',
            value: 'descripcion'
          },
          
          {
            title: 'Archivos',
            value: 'pdf'
          },
          {
            title: 'Acciones',
            value: 'action',
            sortable: false
          },
        ],
      }
    },
    created() {
      this.getAlbaranes()
    },
    methods: {
      callDown(doc){
           
          let imagenes = JSON.parse(doc.pdf) 
          let originaName = window.location.origin + '/'
          let pathServer = 'storage/documentos/userId_' + this.user_id + '/factura_recibidas/'
          let pathDoc = ''
          let documentImagen = ''
          for(var r = 0; r < imagenes.length; r++){
            pathDoc = originaName + pathServer + imagenes[r]
            documentImagen = imagenes[r]
            this.downloadFiles(pathDoc, documentImagen)
          }
           

          
         },
        
         downloadFiles(url, filename) {
         fetch(url).then(function(t) {
           return t.blob().then((b)=>{
               var a = document.createElement("a");
               a.href = URL.createObjectURL(b);
               a.setAttribute("download", filename);
               a.click();
             }
           );
         });
        },
      getAlbaranes() {
        axios.get(`api/get-albaranes/${localStorage.getItem('user_id')}`).then(res => {
          this.albaranes = res.data.albaranes
        }, err => {
          $toast.error('Error consultando albaranes')
        })
      },
      mostrarModalEliminar(item){
        this.modalEliminar = true;
        this.item = item;
      },
      closeModal(){
        this.modalEliminar = false;
        this.item = '';
      },
      deleteAlbaran() {
        this.modalEliminar = false;
        axios.get(`api/delete-albaran/${this.item.id}`).then(res => {
          this.albaranes.splice(this.albaranes.indexOf(this.item), 1)
          $toast.sucs('Albaran eliminado')
          this.item= '';
        }, err => {
          $toast.error('Error eliminando albaran')
        })
      },
      descargarArchivos(item) {
        //quitamos todos los caracteres que no necesitamos
        let archivos = item.pdf.replaceAll('"', '');
        archivos = archivos.replaceAll('[', ''); 
        archivos = archivos.replaceAll(']', ''); 
        
        //convertimos en array
        let array_archivos=archivos.split(',');
        //descargamos todos los archivos
        array_archivos.forEach(element => {
          var archivo = document.createElement('a');
          archivo.setAttribute('href', "https://app.fidifactu.com/storage/albaranes/recibidos/"+element);
          archivo.setAttribute('download', element);
          document.body.appendChild(archivo);
          archivo.click();
        });

      }
    },
    computed: {
      isloading: function() {
        return this.$store.getters.getloading
      },
      user_id(){
        return localStorage.user_id
      }
    }
  }
</script>
