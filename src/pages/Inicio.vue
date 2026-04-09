<template>
    <contabilidad-vue></contabilidad-vue>
    <!--VContainer>
    <VRow>
      
      <VCol
        v-for="item in menu"
        :key="item.title"
        cols="12"
        sm="6"
        md="3"
      >
        <VCard class="cursor-pointer inicio-card" :to="{name: item.children != null ? item.children[0].to : item.to}">
          <VCardText>
            <div class="d-flex justify-space-between pt-2 pb-2">
              <div class="d-flex flex-column gap-y-1">
                <div class="text-body-1 text-high-emphasis">
                  {{ item.title }}
                </div>
                <div class="text-body-2">
                  <small class="text-uppercase" style="font-size: 10px"><b>IR A {{ item.title }}</b></small>
                </div>
              </div>
              <VAvatar
                color="primary"
                variant="tonal"
                rounded
                size="42"
              >
                <VIcon
                  :icon="item.icon.icon"
                  size="30"
                />
              </VAvatar>
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

    <div class="text-center mt-10">
      <img src="@/assets/images/banner/upgrade.png" id="inicio-banner">
    </div>

  </VContainer-->
</template>

<script>
import menu_items from "@/navigation/vertical/index.js";
import ContabilidadVue from "./contabilidad/Contabilidad.vue";

export default {
    components: {
        ContabilidadVue,
    },
    data() {
        return {
            menu_items: menu_items,
        };
    },

    computed: {
        menu() {
            return this.menu_items.filter((item) => item.title !== "Inicio");
        },
    },
};
</script>

<style lang="scss">
.inicio-card:hover {
    box-shadow: 0 4px 5px -2px var(--v-shadow-key-umbra-opacity, #0003),
        0 7px 10px 1px var(--v-shadow-key-penumbra-opacity, #00000024),
        0 2px 16px 1px var(--v-shadow-key-ambient-opacity, #0000001f) !important;
}

@media (max-width: 550px) {
    #inicio-banner {
        width: 100%;
    }
}
</style>
