import {setupLayouts} from "virtual:generated-layouts";
import {createRouter, createWebHistory} from "vue-router";

import SystemParam from "../../pages/system-params/index.vue";
import rutas_albaranes from "./rutas_albaranes";
import rutas_articulos from "./rutas_articulos";
import rutas_clientes from "./rutas_clientes";
import rutas_contabilidad from "./rutas_contabilidad";
import rutas_datos_de_empresa from "./rutas_datos_de_empresa";
import rutas_morosos from "./rutas_morosos";
import rutas_principales from "./rutas_principales";
import rutas_proveedores from "./rutas_proveedores";
import rutas_recibos from "./rutas_recibos";
import rutas_usuarios from "./rutas_usuarios";

import Error from "@/pages/Error.vue";

function recursiveLayouts(route) {
    if (route.children) {
        for (let i = 0; i < route.children.length; i++)
            route.children[i] = recursiveLayouts(route.children[i]);

        return route;
    }

    return setupLayouts([route])[0];
}

const routes = [
    ...rutas_principales,
    ...rutas_clientes,
    ...rutas_proveedores,
    ...rutas_albaranes,
    ...rutas_recibos,
    ...rutas_contabilidad,
    ...rutas_morosos,
    ...rutas_usuarios,
    ...rutas_datos_de_empresa,
    ...rutas_articulos,
    {
        path: "/system-params",
        name: "system-params",
        component: SystemParam,
        meta: {},
    },

    {
        path: "/:pathMatch(.*)*",
        name: "error",
        component: Error,
        meta: {
            layout: "blank",
        },
    },
].map((route) => recursiveLayouts(route));

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),

    scrollBehavior(to) {
        if (to.hash) return {el: to.hash, behavior: "smooth", top: 60};

        return {top: 0};
    },

    routes,
});

router.beforeEach((to) => {
    if (to.meta.Auth) {
        const authUser = localStorage.getItem("role");
        const id_token = localStorage.getItem("id_token");

        if (id_token == null) {
            return {
                path: "/login",
                query: {
                    redirect: to.fullPath,
                },
            };
        } else if (to.meta.req_user)
            return authUser == 2 ? true : {path: "/404"};
        else if (to.meta.req_admin)
            return authUser == 1 ? true : {path: "/404"};
        else if (to.meta.req_admin_or_user)
            return authUser == 1 || authUser == 2 ? true : {path: "/404"};

        return true;
    }

    return true;
});

export {router};
export default function (app) {
    app.use(router);
}
