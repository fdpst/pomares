const items = [
    {
        title: "Inicio",
        icon: { icon: "ri-home-line" },
        to: "inicio",
        user: [1, 2, 3, 4],
    },

    {
        title: "Distribuidores",
        icon: { icon: "ri-truck-line" },
        to: "lista-proveedores",
        user: [1, 2, 3, 4],
    },

    {
        title: "Autofacturas",
        icon: { icon: "ri-bill-line" },
        to: "lista-facturas-recibidas",
        user: [1, 2, 3, 4],
    },

    {
        title: "Liquidaciones",
        icon: { icon: "ri-file-list-3-line" },
        to: "lista-liquidaciones",
        user: [1, 2, 3, 4],
    },

    {
        title: "Artículos",
        icon: { icon: "ri-article-line" },
        to: "lista-productos",
        user: [1, 2, 3, 4],
    },

    {
        title: "Usuarios",
        icon: { icon: "ri-group-2-fill" },
        to: "lista-usuario",
        user: [1],
    },
    {
        title: "Empresa",
        icon: { icon: "ri-user-line" },
        to: "lista-clientes-admin",
        user: [1],
    },

    {
        title: "Contraseñas",
        icon: { icon: "ri-key-fill" },
        to: "lista-passwords",
        user: [1],
    },
];

function get_items_user(user_role) {
    return items.filter((item) => {
        return item.user.some((role) => role == user_role);
    });
}

const user_role = localStorage.getItem("role");

const items_user = get_items_user(user_role);

export default items_user;
