import { menu_items } from '@/navigation/vertical/index.js'

export const menu_items_mixin = {
  data() {
    return {
      items: menu_items
    };
  },

  computed: {
    user() {
      return this.$store.getters.getuser;
    },
    computedheaders: function () {
      if (this.user.role != 0) {
        return this.items.filter((x) => {
          return x.user.some((userole) => userole == this.user.role);
        });
      }
    },
  },
};
