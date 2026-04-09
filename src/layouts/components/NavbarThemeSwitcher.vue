<script setup>
const themes = [
  {
    name: 'system',
    icon: 'ri-macbook-line',
  },
]
</script>

<template>
  <ThemeSwitcher :themes="themes" />
</template>
