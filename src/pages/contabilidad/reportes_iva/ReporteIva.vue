<template>
  <div>
    <VCard shaped class="pa-3 ma-md-3">
      <VToolbar flat color="#1d2735" dark>
        <VIcon class="white--text" style="font-size: 45px"
          >mdi mdi-account-supervisor-circle</VIcon
        >
        <VToolbarTitle
          ><h3 style="overflow-wrap: normal;color: white;">
            Reporte IVA
          </h3></VToolbarTitle
        >
      </VToolbar>

      <VContainer class="mt-3">
        <VCol cols="12">
          <VRow>
            <VCol cols="8">
              <h3 class="text-left">
                IVA 303 Trimestral
              </h3>
            </VCol>
            <VCol>
              <div class="m-0">
                <VSelect label="Seleccionar Año" @change="getDatos()" :items="anio_iva" v-model="anio_iva_selected" small class="text-right" />
              </div>
            </VCol>
          </VRow>
        </VCol>
        <VDivider class="m-2"></VDivider>
        <VRow class="mt-3 w-100">
          <VCol>
            <div class="font-weight-bold">
              Periodo
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              IVA Soportado
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              IVA Repercutido
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              Resultado Iva
            </div>
          </VCol>
        </VRow>

        <VRow >
          <VCol class="text-left" >
            1er. Trimestre
          </VCol>
          <VCol v-for="(impuesto, index) in semestreUno" :key="index" class="text-right" >
            {{ impuesto > 0 ? impuesto : "0.00" }}
          </VCol>
        </VRow>
        <VRow >
          <VCol class="text-left" >
            2do. Trimestre
          </VCol>
          <VCol v-for="(impuesto, index) in semestreDos" :key="index" class="text-right" >
            {{ impuesto > 0 ? impuesto : "0.00" }}
          </VCol>
        </VRow>

        <VRow >
          <VCol class="text-left" >
            3er. Trimestre
          </VCol>
          <VCol v-for="(impuesto, index) in semestreTres" :key="index" class="text-right" >
            {{ impuesto > 0 ? impuesto : "0.00" }}
          </VCol>
        </VRow>

        <VRow >
          <VCol class="text-left" >
            4to. Trimestre
          </VCol>
          <VCol v-for="(impuesto, index) in semestreCuatro" :key="index" class="text-right" >
            {{ impuesto > 0 ? impuesto : "0.00" }}
          </VCol>
        </VRow>

      </VContainer>

      <VContainer class="mt-3">
        <VDivider class="m-2"></VDivider>
        <VRow class="mt-3 w-100">
          <VCol cols="12">
            <h3 class="text-left">
              Resumen de Impuestos
            </h3>
          </VCol>
          <VCol>
            <div class="font-weight-bold">
              Ventas
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              Sub Total
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              Importe
            </div>
          </VCol>
        </VRow>
        <VRow>
          <VCol class="text-left">IVA 21%</VCol>
          <VCol class="text-right">{{ sub_total_ventas }}</VCol>
          <VCol class="text-right">{{ importe_ventas }}</VCol>
        </VRow>
        <VRow class="mt-3 w-100">
          <VCol>
            <div class="font-weight-bold">
              Compras
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              Sub Total
            </div>
          </VCol>
          <VCol>
            <div class="font-weight-bold text-right">
              Importe
            </div>
          </VCol>
        </VRow>
        <VRow>
          <VCol class="text-left">IVA 21 %</VCol>
          <VCol class="text-right">{{ sub_total_compras }}</VCol>
          <VCol class="text-right">{{ importe_compras }}</VCol>
        </VRow>
      </VContainer>

    </VCard>
  </div>
</template>

<script>
// import { debounce } from "../../../helpers";
export default {
  data() {
    return {
      // agentes: [],
      anio_iva_selected: new Date().getFullYear(),
      semestreUno: [],
      semestreDos: [],
      semestreTres: [],
      semestreCuatro: [],
      sub_total_ventas: '',
      sub_total_compras: '',
      importe_ventas : '',
      importe_compras : '',
      anio_iva: [new Date().getFullYear()],
      calculo_iva: []
    };
  },
  created() {
    // this.getEstadosPotencial();
    this.rol = localStorage.getItem("role");
    // this.getAgentes();
    this.getDatos();
    this.getAnios();
  },
  watch: {
    "$route.meta.rol": function (val) {
      this.getDatos();
      this.getAnios();
    },
    "filtros.validacion": function (val) {
      this.getDatos();
      this.getAnios();
    },
  },
  methods: {
    // Get apuntes contables para IVA

    getDatos() {

      axios
        .get(
          `api/get-reporte-iva/${this.anio_iva_selected}`
        )
        .then(
          (res) => {
            if(res.data.code == 200){

              this.semestreUno = [
                parseFloat(res.data.success.semestreUno[0].total_iva_soportado).toFixed(2),
                parseFloat(res.data.success.semestreUno[0].total_iva_repercutido).toFixed(2),
                parseFloat(res.data.success.semestreUno[0].total_iva_repercutido - res.data.success.semestreUno[0].total_iva_soportado).toFixed(2)
              ]

              this.semestreDos = [
                parseFloat(res.data.success.semestreDos[0].total_iva_soportado).toFixed(2),
                parseFloat(res.data.success.semestreDos[0].total_iva_repercutido).toFixed(2),
                parseFloat(res.data.success.semestreDos[0].total_iva_repercutido - res.data.success.semestreDos[0].total_iva_soportado).toFixed(2)
              ]

              this.semestreTres = [
                parseFloat(res.data.success.semestreTres[0].total_iva_soportado).toFixed(2),
                parseFloat(res.data.success.semestreTres[0].total_iva_repercutido).toFixed(2),
                parseFloat(res.data.success.semestreTres[0].total_iva_repercutido - res.data.success.semestreTres[0].total_iva_soportado).toFixed(2)
              ]

              this.semestreCuatro = [
                parseFloat(res.data.success.semestreCuatro[0].total_iva_soportado).toFixed(2),
                parseFloat(res.data.success.semestreCuatro[0].total_iva_repercutido).toFixed(2),
                parseFloat(res.data.success.semestreCuatro[0].total_iva_repercutido - res.data.success.semestreCuatro[0].total_iva_soportado).toFixed(2)
              ]

              this.sub_total_ventas = res.data.success.sub_total_ventas
              this.sub_total_compras = res.data.success.sub_total_compras

              this.importe_ventas = parseFloat(res.data.success.semestreUno[0].total_iva_repercutido + res.data.success.semestreDos[0].total_iva_repercutido + res.data.success.semestreTres[0].total_iva_repercutido + res.data.success.semestreCuatro[0].total_iva_repercutido).toFixed(2)

              this.importe_compras = parseFloat(res.data.success.semestreUno[0].total_iva_soportado + res.data.success.semestreDos[0].total_iva_soportado + res.data.success.semestreTres[0].total_iva_soportado + res.data.success.semestreCuatro[0].total_iva_soportado).toFixed(2)

            }else{
              $toast.error("Error consultando IVA")
            }
          },
          (err) => {
            $toast.error(err.response.data.error);
          }
        );
    },

    getAnios() {

      axios
        .get(
          `api/get-anios-iva`
        )
        .then(
          (res) => {
            if(res.data.code == 200){
              console.log(res.data.success)
              this.anio_iva = res.data.success
              console.log(this.anio_iva)
            }else{
              $toast.error("Error consultando años contables")
            }
          },
          (err) => {
            $toast.error(err.response.data.error);
          }
        );
      },
  },
};
</script>


<style scoped>
</style>
