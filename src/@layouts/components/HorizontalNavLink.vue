<script setup>
import { layoutConfig } from '@layouts'
import { can } from '@layouts/plugins/casl'
import {
  getDynamicI18nProps,
  isNavLinkActive,
} from '@layouts/utils'

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
  isSubItem: {
    type: Boolean,
    required: false,
    default: false,
  },
})

const router = useRouter()

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
    v-if="can(item.action, item.subject)"
    class="nav-link"
    :class="[{
      'sub-item': props.isSubItem,
      'disabled': item.disable,
    }]"
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
        class="nav-item-icon"
        v-bind="item.icon || layoutConfig.verticalNav.defaultNavItemIconProps"
      />
      <Component
        :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
        class="nav-item-title"
        v-bind="getDynamicI18nProps(item.title, 'span')"
      >
        {{ item.title }}
      </Component>
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
        class="nav-item-icon"
        v-bind="item.icon || layoutConfig.verticalNav.defaultNavItemIconProps"
      />
      <Component
        :is="layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
        class="nav-item-title"
        v-bind="getDynamicI18nProps(item.title, 'span')"
      >
        {{ item.title }}
      </Component>
    </a>
  </li>
</template>

<style lang="scss">
.layout-horizontal-nav {
  .nav-link a {
    display: flex;
    align-items: center;
  }
}
</style>
