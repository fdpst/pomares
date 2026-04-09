<template>
  <VMenu
    ref="menu"
    v-bind="menu"
    :close-on-content-click="false"
    :return-value.sync="local_date"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on, attrs }">
      <VTextField
        filled
        v-model="local_date"
        :label="label"
        prepend-icon="ri-calendar-line"
        readonly
        v-bind="attrs"
        v-on="on"
      ></VTextField>
    </template>

    <v-date-picker
      first-day-of-week="1"
      v-model="local_date"
      no-title
      scrollable
    >
      <VSpacer></VSpacer>

      <VBtn text color="primary" @click="closeMenu"> Cancel </VBtn>

      <VBtn text color="primary" @click="setDate"> OK </VBtn>
    </v-date-picker>
  </VMenu>
</template>

<script>
export default {
  props: ["menu", "date", "label"],

  data() {
    return {
      local_date: "",
    };
  },

  watch: {
    date(n) {
      local_date = n;
    },
  },

  methods: {
    setDate(date) {
      console.log(date);
      //this.$refs.menu.save(date)
      //this.$emit('set_date', date)
    },
    closeMenu() {
      this.$emit("close_menu");
    },
  },
};
</script>
