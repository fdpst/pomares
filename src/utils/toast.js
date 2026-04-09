import Swal from 'sweetalert2'


const SwalToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})


export const $toast = {
  
  sucs: text => {
    SwalToast.fire({
      text: text,
      icon: 'success'
    })
  },

  error: text => {
    SwalToast.fire({
      text: text,
      icon: 'error'
    })
  },

  info: text => {
    SwalToast.fire({
      text: text,
      icon: 'info'
    })
  },

  warn: text => {
    SwalToast.fire({
      text: text,
      icon: 'warning'
    })
  }

}