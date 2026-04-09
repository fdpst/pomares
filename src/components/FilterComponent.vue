<template>

  <VRow>

    <VCol cols="10">
      <VTextField
        v-model="result.search"
        label="Búsqueda"
      ></VTextField>
    </VCol>
    

    <VCol cols="2">

      <VMenu
        :location="location"
        :close-on-content-click="false"
        nudge-bottom="35"
      >
        <template v-slot:activator="{ props }">
          <VBtn 
            color="primary" 
            dark 
            v-bind="props" 
            class="mt-1"
          >
            <VIcon color="white">ri-filter-fill</VIcon>
          </VBtn>
        </template>

         <VList style="min-width: 20rem">
          <VListItem
            v-for="(item, index) in headers"
            :key="index"
          >
            <div
              style="
                display: flex;
                flex-direction: column;
                width: 100%;
                padding-top: 0.25rem;
              "
            >

              <!-- icono para expandir y contraer selec tipo -->
              <div
                @click="activarItem(item)"
                style="
                  display: flex;
                  cursor: pointer;
                  justify-content: space-between;
                  flex-direction: row;
                  width: 100%;
                "
              >
                <div>{{ item.title }}</div>
                <div style="flex: 1"></div>
                <svg
                  :class="`icon ${
                    item.active ? 'upside-down' : ''
                  }`"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  style="width: 1rem; height: 1rem"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                  />
                </svg>
              </div>


              <div
                :class="`menu ${ item.active ? 'active' : '' }`"
                v-if="item.item != null"
              >

                <!-- item de tipo fecha -->
                <div v-if="item.type == 'date'">

                  <VSelect
                    class="mt-3"
                    :modelValue="item.item.tipo"
                    @update:modelValue="val => updateTipo(item.item, val)"
                    label="tipo"
                    :items="opciones_fecha"
                  ></VSelect>

                  <AppDateTimePicker
                    class="mt-3"
                    v-model="result[item.model].start"
                    label="Desde"
                    prepend-icon="ri-calendar-fill"
                    v-if="item.item.tipo != 2"
                  />

                  <AppDateTimePicker
                    class="mt-3"
                    v-model="result[item.model].end"
                    label="Hasta"
                    prepend-icon="ri-calendar-fill"
                    v-if="item.item.tipo != 1"
                  />

                </div>


                <div v-else-if="item.type == 'select'">
                  <VSelect
                    v-model="result[item.model].value"
                    label="Seleccione uno"
                    @input="changeRes(item)"
                    :items="item.items"
                    :item-title="item.item_text"
                    :item-value="item.item_value"
                  ></VSelect>
                </div>


                <div v-else-if="item.type == 'check'">
                  <VCheckbox
                    v-model="result[item.model].value"
                    label="seleccionar"
                  ></VCheckbox>
                </div>


                <div v-else>
                  <VTextField
                    v-model="result[item.model].value"
                    :item-text="
                      item.item_text == null
                        ? 'nombre'
                        : item.item_text
                    "
                    :item-value="
                      item.item_value == null
                        ? 'id'
                        : item.item_value
                    "
                    label="Búsqueda"
                  ></VTextField>
                </div>


              </div>
            </div> 
          </VListItem>
        </VList>

      </VMenu>

    </VCol>


    <!-- listado de filtros aplicados -->
    <VCol cols="12">
      <div style="width: 100%; display: flex; flex-wrap: wrap">
        <template v-for="key in Object.keys(result)">
          <template v-if="result[key]">
            <VChip
              style="margin-bottom: 0.25rem"
              v-if="
                (result[key].value != '') &
                  (result[key].value != null) ||
                (result[key].nombre != '') &
                  (result[key].nombre != null) ||
                (result[key].start != '') &
                  (result[key].start != null) ||
                (result[key].end != '') &
                  (result[key].end != null)
              "
            >
              <div style="display: flex; align-items: center">
                <div style="padding-right: 1rem">
                  {{ getChipData(key) }}
                </div>
                <div
                  style="
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                  "
                  @click="deleteItem(key)"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="icon"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </div>
              </div>
            </VChip>
          </template>
        </template>
      </div>
    </VCol>



  </VRow>
  
</template>



<style>
.menu {
  overflow: hidden;
  max-height: 0px;
  transition: max-height 0.3s;
}

.menu.active {
  display: block;
  max-height: 200px;
}
.icon {
  width: 1rem;
  height: 1rem;
  transition: transform 0.3s;
}

.icon.upside-down {
  transform: rotate(180deg);
}
</style>



<script>
export default {
  props: ["modelValue", "headers"],
  created() {
    this.result = this.modelValue ?? {};
    this.checkItems();
  },
  data() {
    return {
      dialog: false,
      result: {},
      opciones_fecha: [
        { value: 1, title: "Desde" },
        { value: 2, title: "Hasta" },
        { value: 3, title: "Entre" },
      ],

      locations: ["top", "bottom", "start", "end", "center"],
      location: "top",
    };
  },
  watch: {
    modelValue: {
      deep: true,
      handler(val) {
        this.result = val ?? {};
        this.checkItems();
      },
    },
    result: {
      deep: true,
      handler(val) {
        this.$emit("update:modelValue", val);
      },
    },
  },
  methods: {
    changeRes(item) {
      //console.log("cambiando");
      this.result[item.model].nombre = item.items.find((element) => {
        return (
          element[item.item_value ?? "value"] ==
          this.result[item.model].value
        );
      })[item.item_text];
    },
    getChipData(key) {
      let result = `${key}, `;
      if (this.result[key].nombre != null) {
        result += this.result[key].nombre;
      } else if (
        this.result[key].start != null ||
        this.result[key].end != null
      ) {
        result += `${this.result[key].start}${
          (this.result[key].start != null) &
          (this.result[key].end != null)
            ? " - "
            : ""
        }
      ${this.result[key].end}`;
      } else {
        result += this.result[key].value;
      }

      return result;
    },
    deleteItem(key) {
      this.result[key].value = "";
      this.result[key].start = "";
      this.result[key].end = "";
      this.result[key].nombre = "";
    },
    updateTipo(model, value) {
      console.log(value)
      model.tipo = value;
      const resultado = JSON.stringify(this.result);
      this.result = [];
      this.result = JSON.parse(resultado);
    },
    checkItems() {
      let changed = false;
      this.headers.forEach((element) => {
        if (this.result[element.model ?? element.title] == null) {
          this.result[element.model ?? element.title] = { tipo: 1 };
          changed = true;
          if (element.type == "date") {
            this.result[element.model ?? element.title].end = "";
            this.result[element.model ?? element.title].start = "";
          } else {
            this.result[element.model ?? element.title].value = "";
          }
        }
        element.item = this.result[element.model ?? element.title];
      });
      if (changed) this.result = JSON.parse(JSON.stringify(this.result));
    },
    activarItem(_item) {
      this.headers.forEach((item) => {
        if (item == _item) {
          item.active = !item.active;
        } else {
          item.active = false;
        }
      });
    },
  },
};
</script>
