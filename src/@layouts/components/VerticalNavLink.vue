<script setup>
import { layoutConfig } from '@layouts'
import { useLayoutConfigStore } from '@layouts/stores/config'
import {
  getDynamicI18nProps,
  isNavLinkActive,
} from '@layouts/utils'

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})

const router = useRouter()
const configStore = useLayoutConfigStore()
const hideTitleAndBadge = configStore.isVerticalNavMini()

/**
 * Destino del enlace (misma lógica que antes en utils).
 * No usamos `RouterLink` + `navigate(e)`: si un hijo (p. ej. VIcon) hace `preventDefault`,
 * `guardEvent` de vue-router no navega y el clic parece “muerto”.
 */
const linkTo = computed(() => {
  const t = props.item.to
  if (typeof t === 'string')
    return t.startsWith('/') ? t : { name: t }

  return t
})

const resolvedHref = computed(() => {
  try {
    return router.resolve(linkTo.value).href
  }
  catch {
    return '#'
  }
})

/** Captura: corre antes que hijos (VIcon, etc.); `router.push` no depende de `guardEvent` ni de defaultPrevented en burbuja. */
function onNavClickCapture(e) {
  if (props.item.disable)
    return
  if (e.metaKey || e.altKey || e.ctrlKey || e.shiftKey)
    return
  if (e.button !== undefined && e.button !== 0)
    return
  e.preventDefault()
  e.stopPropagation()
  router.push(linkTo.value).catch(() => {})
}
</script>

<template>
  <li
    class="nav-link"
    :class="{ disabled: item.disable }"
  >
    <a
      v-if="item.to"
      :href="resolvedHref"
      class="layout-nav-link-anchor"
      :class="{ 'router-link-active router-link-exact-active': isNavLinkActive(item, router) }"
      @click.capture="onNavClickCapture"
    >
      <Component
        :is="layoutConfig.app.iconRenderer || 'div'"
        v-bind="item.icon || layoutConfig.verticalNav.defaultNavItemIconProps"
        class="nav-item-icon"
      />
      <TransitionGroup name="transition-slide-x">
        <!-- 👉 Title -->
        <Component
          :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
          v-show="!hideTitleAndBadge"
          key="title"
          class="nav-item-title"
          v-bind="getDynamicI18nProps(item.title, 'span')"
        >
          {{ item.title }}
        </Component>

        <!-- 👉 Badge -->
        <Component
          :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
          v-if="item.badgeContent"
          v-show="!hideTitleAndBadge"
          key="badge"
          class="nav-item-badge"
          :class="item.badgeClass"
          v-bind="getDynamicI18nProps(item.badgeContent, 'span')"
        >
          {{ item.badgeContent }}
        </Component>
      </TransitionGroup>
    </a>
    <a
      v-else-if="item.href"
      :href="item.href"
      :target="item.target"
      :rel="item.rel"
      :class="{ 'router-link-active router-link-exact-active': isNavLinkActive(item, router) }"
    >
      <Component
        :is="layoutConfig.app.iconRenderer || 'div'"
        v-bind="item.icon || layoutConfig.verticalNav.defaultNavItemIconProps"
        class="nav-item-icon"
      />
      <TransitionGroup name="transition-slide-x">
        <Component
          :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
          v-show="!hideTitleAndBadge"
          key="title"
          class="nav-item-title"
          v-bind="getDynamicI18nProps(item.title, 'span')"
        >
          {{ item.title }}
        </Component>
        <Component
          :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
          v-if="item.badgeContent"
          v-show="!hideTitleAndBadge"
          key="badge"
          class="nav-item-badge"
          :class="item.badgeClass"
          v-bind="getDynamicI18nProps(item.badgeContent, 'span')"
        >
          {{ item.badgeContent }}
        </Component>
      </TransitionGroup>
    </a>
  </li>
</template>

<style lang="scss">
.layout-vertical-nav {
  .nav-link a,
  .nav-link .layout-nav-link-anchor {
    display: flex;
    align-items: center;
  }
}
</style>
