<template>
  <VCard outlined class="mx-auto">

    <VToolbar color="blue" flat dark>
      <VToolbarTitle>
        <slot name="title"></slot>
      </VToolbarTitle>
    </VToolbar>

    <VContainer fluid>
      <slot name="content"></slot>
    </VContainer>

  </VCard>
</template>

<script>
  export default {}
</script>