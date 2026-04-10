const ListaLiquidaciones = () =>
    import("@/pages/recibos/ListaLiquidaciones.vue");
const FormLiquidaciones = () =>
    import("@/pages/recibos/FormLiquidaciones.vue");
const FormLiquidacionesUpdate = () =>
    import("@/pages/recibos/FormLiquidacionesUpdate.vue");

const routes = [
    {
        path: "/lista-liquidaciones",
        name: "lista-liquidaciones",
        component: ListaLiquidaciones,
        meta: {
            Auth: true,
        },
    },
    {
        path: "/form-liquidaciones",
        name: "form-liquidaciones",
        component: FormLiquidaciones,
        meta: {
            Auth: true,
        },
    },
    {
        path: "/form-liquidaciones-update/:idLiquidacion",
        name: "form-liquidaciones-update",
        component: FormLiquidacionesUpdate,
        meta: {
            Auth: true,
        },
    },
];

export default routes;
