<template>
  <div class="h-100 d-flex align-center justify-space-between text-medium-emphasis">
    <!-- 👉 Footer: left content -->
    <span class="d-flex align-center">
      &copy;
      {{ new Date().getFullYear() }}
      Hecho con
      <VIcon
        icon="ri-heart-line"
        color="error"
        size="1.25rem"
        class="mx-1"
      />
      por <a
        href="https://fidiaspro.com"
        target="_blank"
        rel="noopener noreferrer"
        class="text-primary ms-1"
      >FidiasPro</a>
    </span>
  </div>
</template>
