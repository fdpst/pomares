<script>
import logo from "@/assets/images/logo_login.webp"
import logoTexto from "@/assets/images/logo_texto.webp"

export default {
  name: 'recuperar-contrasena',

  data() {
    return {
      logo: logo,
      logoTexto: logoTexto,
      user: {
        email: ''
      },
      error: null
    }
  },


  methods: {
    
    recoverPassword() 
    {
      this.error = null

      this.$refs.refVForm.validate().then(({ valid: isValid }) => 
      {
        if (!isValid)
          return

        axios.post('api/recover-password', this.user).then(res => {

          $toast.sucs('Mail enviado con exito!')

        }).catch(error => {

          if (error.response.status == 401) 
            this.error = error.response.data.message[0]

          $toast.error('Error enviando mail de recuperación , Mail Invalido');

        }) 

      })
    },

  },


  computed: {
    isloading: function() {
      return this.$store.getters.getloading
    }
  }

}

</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <img :src="logoTexto" alt="Martí Pomares, S.L" class="auth-logo-img">
    </div>
  </RouterLink>

  <VRow
    class="auth-wrapper"
    no-gutters
  >
    <VCol
      md="8"
      class="d-none d-md-flex position-relative"
    >
      <div class="d-flex align-center justify-center w-100 h-100 pa-10 pe-0">
        <VImg
          max-width="300"
          :src="logo"
          class="auth-illustration"
        />
      </div>


      <!-- <img
        :src="tree1"
        alt="tree image"
        height="140"
        class="auth-footer-tree"
      > -->
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4 app-recover__card"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            ¿Recuperar contraseña? 🔒
          </h4>
          <p class="mb-0">
            Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña
          </p>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="recoverPassword">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="user.email"
                  autofocus
                  label="Correo electrónico"
                  placeholder="johndoe@email.com"
                  type="email"
                  variant="solo"
                  color="#DCFF2E"
                  base-color="rgba(255, 255, 255, 0.8)"
                  :rules="[requiredValidator, emailValidator]"
                  class="app-recover__input"
                />
              </VCol>

              <!-- errores -->
              <h4 class="app-recover__error" v-if="error != null">
                {{ error }}
              </h4>

              <!-- Reset link -->
              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                  :disabled="isloading"
                  class="app-recover__submit"
                  color="#DCFF2E"
                >
                  Recuperar
                </VBtn>
              </VCol>

              <!-- back to login -->
              <VCol cols="12">
                <RouterLink
                  class="d-flex align-center justify-center forgot-password-link"
                  to="/login"
                >
                  <VIcon
                    icon="ri-arrow-left-s-line"
                    class="flip-in-rtl"
                  />
                  <span>Volver al inicio de sesión</span>
                </RouterLink>
              </VCol>

            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

</template>


<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";

// Fondo con gradiente del login
.auth-wrapper {
  background: linear-gradient(135deg, #5142A6 0%, #B6A0E1 100%) !important;
  min-height: 100vh;
}

// Logo de texto arriba
.auth-logo-img {
  max-width: clamp(200px, 30vw, 300px);
  width: 100%;
}

.auth-title {
  color: #DCFF2E;
  font-weight: 700;
}

// Card styling
.app-recover__card {
  background-color: transparent !important;
  
  .v-card-text {
    color: #FAFAFA;
  }
}

// Títulos y texto dentro del card
.app-recover__card .text-h4 {
  color: #DCFF2E !important;
}

.app-recover__card .mb-0 {
  color: #FAFAFA !important;
}

.app-recover__card p {
  color: #FAFAFA !important;
}

// Inputs con estilo del login
.app-recover__input {
  .v-field {
    border-radius: 16px;
    background-color: rgba(57, 26, 140, 0.65);
    box-shadow: none;
  }

  .v-field__overlay {
    background-color: transparent;
  }

  .v-field__outline {
    display: none;
  }

  .v-field__input {
    text-align: center;
    color: #DCFF2E;
    font-weight: 500;
    letter-spacing: 0.02em;

    &::placeholder {
      color: rgba(220, 255, 46, 0.85);
    }
  }

  .v-label {
    justify-content: center;
    color: #DCFF2E;
    font-weight: 500;
  }
}

// Errores
.app-recover__error {
  text-align: center;
  color: #DCFF2E;
  font-weight: 400;
  margin: -0.5rem 0 0;
}

// Botón con estilo del login
.app-recover__submit {
  background-color: #DCFF2E !important;
  color: #000000 !important;
  font-weight: 700;
  border-radius: 999px;
  text-transform: none;
  letter-spacing: 0.05em;
  min-height: 44px;
  box-shadow: none;
  border: none;

  &:hover,
  &:focus,
  &:active {
    background-color: #DCFF2E !important;
    color: #000000 !important;
    box-shadow: none;
  }

  .v-btn__content {
    color: #000000 !important;
    text-transform: none;
    font-weight: 700;
    letter-spacing: 0.05em;
  }
}

// Enlace de vuelta al login
.forgot-password-link {
  color: #FAFAFA !important;
  text-decoration: underline;
  font-weight: 500;
  transition: color 0.2s ease;

  &:hover {
    color: #DCFF2E !important;
  }

  .v-icon {
    color: #FAFAFA !important;
    transition: color 0.2s ease;
  }

  &:hover .v-icon {
    color: #DCFF2E !important;
  }
}

// Card background
.auth-card-v2 {
  background-color: transparent !important;
}

// Asegurar que todos los textos del card sean visibles
.auth-card-v2 .v-card {
  color: #FAFAFA;
}

.auth-card-v2 .v-card-text {
  color: #FAFAFA;
}
</style>
