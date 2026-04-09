<!-- <template functional>
  <VCol class="pa-4" cols="12" md="3">
    <VRow class="mt-4" v-if="props.recibo.total_descuento!=0">
      <b>Subtotal:</b>
      <VSpacer></VSpacer>
      {{ props.recibo.sub_total }}€
    </VRow>
    <VRow class="mt-4" v-if="props.recibo.total_descuento!=0">
      <b>Descuento:</b>
      <VSpacer></VSpacer>
      {{ props.recibo.total_descuento }}€
    </VRow>
    <VRow class="mt-4">
      <b>Total:</b>
      <VSpacer></VSpacer>
      {{ props.total }}€
    </VRow>
  </VCol>
</template> -->

<template>
  <VCol class="pa-4" cols="12" md="3">
    <VRow class="mt-4" v-if="recibo.total_descuento!=0">
      <b>Subtotal:</b>
      <VSpacer></VSpacer>
      {{ formatPrice(recibo.sub_total) }}€
    </VRow>
    <VRow class="mt-4" v-if="recibo.total_descuento!=0">
      <b>Descuento:</b>
      <VSpacer></VSpacer>
      {{ formatPrice(recibo.total_descuento) }}€
    </VRow>
    <VRow class="mt-4">
      <b>Total:</b>
      <VSpacer></VSpacer>
      {{ formatPrice(total) }}€
    </VRow>
  </VCol>
</template>

<script>
  export default {
    props: ['total', 'recibo']
  }
</script>
