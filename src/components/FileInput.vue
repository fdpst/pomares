<template>

  <div>
    <input 
      type="file" 
      ref="file"  
      @change="onChange" 
      multiple
    >
  </div>

</template>


<script>

export default{
  
  props: {
    
  },

  data:() => ({
    files: [],
    showPreview: '',
    imagePreview:[], 
  }),

  mounted(){
    this.getImagesP()   
  },

  methods:{

    showFilePicker(){
      this.$refs.file.click()
    },

    getImagesP()
    {
      let photo = (this.imagePreview.length > 100) ? '' :  this.imagePreview;
      
      return photo;
    },

    onChange(e)
    {
      const filesPreview = e.currentTarget.files;

      Object.keys(filesPreview).forEach(i => 
      {
        const file = filesPreview[i];
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview.push(reader.result);
        }
        this.imagePreview = []
        reader.readAsDataURL(file);
      });


      this.files = e.target.files[0] //subir una
      let files = e.target.files //subir varias
      this.$emit('file-change', files)
    },

    getFilesName()
    {
      let filesName = []

      if (this.files.length > 0) {
        for (let file of this.files) 
        {
          filesName.push(file.name)
        }
      }

      return filesName.join(", ")
    },

    clearFiles()
    {
      this.files = []
      this.disableUploadButtonImage = true
    },

    limpiar()
    {
      const input = this.$refs.file
      input.type = 'text'
      input.type = 'file'
    }
    
  }
}

</script>