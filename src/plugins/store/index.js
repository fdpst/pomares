import modulo_clientes from "./modulos/modulo_clientes";
import modulo_estado from "./modulos/modulo_estado";
import modulo_filtros from "./modulos/modulo_filtros";
import modulo_paises from "./modulos/modulo_paises";
import modulo_provincias from "./modulos/modulo_provincias";

import { createStore } from "vuex";

export const store = createStore({
    strict: false,

    modules: {
        estado: modulo_estado,
        provincias: modulo_provincias,
        paises: modulo_paises,
        clientes: modulo_clientes,
        filtros: modulo_filtros,
    },
});

export default function (app) {
    app.use(store);
}
