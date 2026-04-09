<script setup>
import navItems from '@/navigation/horizontal'
import { themeConfig } from '@themeConfig'

// Components
import Footer from '@/layouts/components/Footer.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import { HorizontalNavLayout } from '@layouts'
</script>

<template>
  <HorizontalNavLayout :nav-items="navItems">
    <!-- 👉 navbar -->
    <template #navbar>
      <RouterLink
        to="/"
        class="d-flex align-start gap-x-4"
      >
        <!-- <VNodeRenderer :nodes="themeConfig.app.logo" /> -->
        <img src="@/assets/images/logo.png" width="30">

        <h1 class="leading-normal text-xl text-uppercase">
          {{ themeConfig.app.title }}
        </h1>
      </RouterLink>
      <!-- Conmutador de clientes (si es gestor) - a la izquierda -->
      <div class="me-4">
        <UserProfile selector-only />
      </div>

      <VSpacer />

      <!-- <NavSearchBar /> -->

      <!-- <NavBarI18n
        v-if="themeConfig.app.i18n.enable && themeConfig.app.i18n.langConfig?.length"
        :languages="themeConfig.app.i18n.langConfig"
      /> -->

      <!-- <NavbarShortcuts /> -->
      <!-- <NavBarNotifications class="me-2" /> -->
      
      <!-- Icono de usuario - a la derecha -->
      <UserProfile user-icon-only />
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </HorizontalNavLayout>
</template>
