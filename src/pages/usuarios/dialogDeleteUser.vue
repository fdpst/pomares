<template>
  <div>

    <ConfirmDialog
      v-model="openDialog"
      @cancel="closedialogDeleteUser"
      @confirm="deleteUser"
      text="Eliminar Usuario"
    />

  </div>
</template>
<script>
   export default {
    props: {
      dialogDeleteUser: Boolean,
      closedialogDeleteUser: Function,
      captureItem: Object,
    },

    data() { 
      return {
        openDialog: false
      } 
    },

    watch: {
      dialogDeleteUser: function() {
        this.openDialog = this.dialogDeleteUser
      }
    },

    created() { 
    },

    methods:{
      deleteUser(){   
        
        axios.get(`api/delete-usuario/${this.captureItem.item.id}`).then(res => {
          $toast.sucs('Usuario eliminado');
          this.closedialogDeleteUser();
        }, err => {
          $toast.error('Error eliminando Usuario')
        })
      },
    }
   };
</script>

<style>
  .tittlecard {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
    margin-bottom: 30px !important;
    background-color: #FDCB25 !important;
  }
</style>