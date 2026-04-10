<script>
import logo from "@/assets/images/logo_login.webp"

export default {
  name: 'login',

  data() {
    return {
      logo: logo,
      user: {
        email: '',
        password: ''
      },

      rememberMe: false,
      isPasswordVisible: false,

      error: null
    }
  },

  mounted() {
    if (localStorage != undefined) {
      let valid = localStorage.user_id

      if (valid)
        window.location.href = '/'
    }
  },

  methods: {
    login() {
      this.error = null

      this.$refs.refVForm.validate().then(({ valid: isValid }) => {
        if (!isValid)
          return

        axios.post('api/login', this.user).then(response => {
          if (typeof response?.data?.token != "undefined") {
            localStorage.setItem('id_token', response.data.token)
            localStorage.setItem('user_name', response.data.user.name)
            localStorage.setItem('user_email', response.data.user.email)
            localStorage.setItem('role', response.data.user.role)
            localStorage.setItem('user_id', response.data.user.id)

            const cookieStr = JSON.stringify(response.data.userAbilityRules)
            document.cookie = 'userAbilityRules=' + cookieStr

            window.location.href = '/'
          }
          else {
            this.error = 'Algo ha salido mal'
          }

        }).catch(error => {
          if (error.response?.status == 401)
            this.error = error.response.data.message[0]
          else
            this.error = 'Algo ha salido mal'
        })
      })
    }

  },


  computed: {
    isloading: function() {
      return this.$store.getters.getloading
    }
  }

}


</script>


<template>
  <div class="app-login">
    <RouterLink
      to="/"
      class="app-login__logo"
    >
      <img
        :src="logo"
        alt="Martí Pomares, S.L"
      >
    </RouterLink>

    <div class="app-login__card">
      <h1 class="app-login__title">
        ¡Bienvenido!
      </h1>

      <VForm
        ref="refVForm"
        class="app-login__form"
        @submit.prevent="login"
      >
        <VTextField
          v-model="user.email"
          label="Usuario"
          placeholder="Usuario"
          type="email"
          autofocus
          hide-details="auto"
          variant="solo"
          color="#DCFF2E"
          base-color="rgba(255, 255, 255, 0.8)"
          :rules="[requiredValidator, emailValidator]"
          class="app-login__input"
        />

        <VTextField
          v-model="user.password"
          label="Contraseña"
          placeholder="Contraseña"
          hide-details="auto"
          variant="solo"
          color="#DCFF2E"
          base-color="rgba(255, 255, 255, 0.8)"
          :rules="[requiredValidator]"
          :type="isPasswordVisible ? 'text' : 'password'"
          :append-inner-icon="isPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
          @click:append-inner="isPasswordVisible = !isPasswordVisible"
          class="app-login__input"
        />

        <h4
          v-if="error != null"
          class="app-login__error"
        >
          {{ error }}
        </h4>

        <div class="app-login__options">
          <VCheckbox
            v-model="rememberMe"
            label="Recuérdame"
            hide-details
            color="#DCFF2E"
            class="app-login__checkbox"
          />

          <RouterLink
            to="/recuperar-contrasena"
            class="app-login__link"
          >
            ¿Has olvidado tu contraseña?
          </RouterLink>
        </div>

        <VBtn
          type="submit"
          class="app-login__submit"
          :disabled="isloading"
          color="#DCFF2E"
        >
          iniciar sesión
        </VBtn>
      </VForm>
    </div>

    <p class="app-login__footer">
      2025 ©
    </p>
  </div>
</template>


<style lang="scss">
.app-login {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  background: linear-gradient(135deg, #5142A6 0%, #B6A0E1 100%);
  color: #FAFAFA;
  font-family: 'area_normal', 'Area Normal', 'Roboto', sans-serif;

  &__logo {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;

    img {
      max-width: clamp(260px, 38vw, 360px);
      width: 100%;
    }
  }

  &__card {
    width: 100%;
    max-width: 420px;
    background: transparent;
    backdrop-filter: none;
    border-radius: 0;
    padding: 2.5rem 2rem 2rem;
    box-shadow: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
  }

  &__title {
    font-size: clamp(2.4rem, 4vw, 3.1rem);
    font-weight: 700;
    color: #DCFF2E;
    text-align: center;
    margin: 0;
  }

  &__form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
  }

  &__input {
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

  &__error {
    text-align: center;
    color: #DCFF2E;
    font-weight: 400;
    margin: -0.5rem 0 0;
  }

  &__options {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;

    @media (min-width: 480px) {
      flex-direction: row;
      justify-content: space-between;
      width: 100%;
    }
  }

  &__checkbox {
    .v-selection-control {
      color: #FAFAFA;
      align-items: center;
    }

    .v-selection-control__input {
      color: #FFFFFF !important;
      background-color: transparent !important;
    }

    .v-selection-control__wrapper {
      border: 2px solid rgba(255, 255, 255, 0.9);
      border-radius: 6px;
      background-color: transparent;
      width: 22px;
      height: 22px;
    }

    .v-selection-control__input .v-icon {
      color: #343434 !important;
      background-color: transparent;
    }

    .v-label--clickable {
      color: #FAFAFA !important;
    }
  }

  &__link {
    color: #FAFAFA;
    text-decoration: underline;
    font-weight: 500;

    &:hover {
      color: #DCFF2E;
    }
  }

  &__submit {
    background-color: #DCFF2E !important;
    color: #000000;
    font-weight: 700;
    border-radius: 999px;
    text-transform: none;
    letter-spacing: 0.05em;
    min-height: 44px;
    box-shadow: none;
    border: none;
    max-width: 160px;
    margin: 0 auto;
    display: inline-flex;

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

  &__footer {
    margin-top: 2.5rem;
    font-size: 0.95rem;
    color: #FAFAFA;
  }
}

@media (max-width: 420px) {
  .app-login {
    padding: 1.5rem 1rem;

    &__card {
      padding: 2rem 1.5rem;
    }
  }
}
</style>
