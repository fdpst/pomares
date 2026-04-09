const modulo_clientes = {
    strict: false,

    state() {
        return {
            clientes: [],
            metodos_pago: [],
            current: 0,
            page: 1, // ✅ Inicializar page correctamente
            hasMorePages: true, // ✅ Añadir control de páginas disponibles
            isSearching: false, // ✅ Añadir estado de búsqueda
            searchTerm: "", // ✅ Guardar término de búsqueda actual
            userId: localStorage.getItem("user_id"),
        };
    },

    getters: {
        getclientes: (state) => {
            return state.clientes;
        },
        getmetodoscliente: (state) => state.metodos_pago,
        hasMorePages: (state) => state.hasMorePages,
        isSearching: (state) => state.isSearching,
    },

    mutations: {
        get_clientes: (state, response) => {
            // ✅ Manejar tanto array simple como objeto con paginación
            if (Array.isArray(response)) {
                state.clientes = response;
                state.page = 2;
                state.hasMorePages = response.length === 15; // Asumir que hay más si llegaron 15
            } else {
                state.clientes = response.data || response;
                state.page = (response.current_page || 1) + 1;
                state.hasMorePages = response.current_page < response.last_page;
            }
        },

        update_clientes: (state, response) => {
            // ✅ Solo concatenar si no estamos buscando
            if (!state.isSearching) {
                state.clientes = state.clientes.concat(response.data);
                state.page = response.current_page + 1;
                state.hasMorePages = response.current_page < response.last_page;
            }
        },

        search_clientes: (state, response) => {
            // ✅ Reemplazar completamente en búsquedas
            state.clientes = response.data || response;
            state.page = (response.current_page || 1) + 1;
            state.hasMorePages = response.current_page < response.last_page;
        },

        set_search_state: (state, { isSearching, searchTerm = "" }) => {
            state.isSearching = isSearching;
            state.searchTerm = searchTerm;
            if (!isSearching) {
                // ✅ Reset pagination cuando se limpia la búsqueda
                state.page = 1;
                state.hasMorePages = true;
            }
        },

        add_search: (state, clientes) => {
            state.clientes = state.clientes.concat(clientes);
        },

        get_data_cliente: (state, data) => {
            state.metodos_pago = data.metodos_pago;
        },

        change_rol_cliente: (state, data) => {
            state.rol = data;
        },

        add_cliente: (state, data) => {
            state.clientes.push(data);
        },
    },

    actions: {
        addCliente(context, vm) {
            context.commit("add_cliente", vm);
        },

        getClientes: (context, vm) => {
            // ✅ Reset search state when loading initial clients
            context.commit("set_search_state", {
                isSearching: false,
                searchTerm: "",
            });

            axios
                .get(`api/get-clientes`, {
                    params: {
                        amount: 15,
                        page: 1,
                    },
                })
                .then(
                    (res) => {
                        context.commit("get_clientes", res.data);
                    },
                    (res) => {
                        console.error("getClientes error:", res);
                    }
                );
        },

        getClientesWithId: (context, vm) => {
            context.commit("set_search_state", {
                isSearching: false,
                searchTerm: "",
            });

            axios
                .get(`api/get-clientes`, {
                    params: {
                        amount: 15,
                        page: 1,
                        cliente: vm.id,
                    },
                })
                .then(
                    (res) => {
                        context.commit("get_clientes", res.data);
                    },
                    (res) => {}
                );
        },

        getClientesNext: (context, vm) => {
            // ✅ Validaciones mejoradas para paginación
            if (!context.state.hasMorePages || context.state.page < 2) {
                return;
            }

            const params = {
                amount: 15,
                page: context.state.page,
            };

            // ✅ Si estamos en modo búsqueda, incluir el término
            if (context.state.isSearching && context.state.searchTerm) {
                params.search = context.state.searchTerm;
            }

            axios
                .get(`api/get-clientes`, { params })
                .then(
                    (res) => {
                        if (context.state.isSearching) {
                            // ✅ En búsqueda, concatenar resultados de búsqueda
                            const newClients = res.data.data || res.data;
                            context.commit("add_search", newClients);
                            context.state.page =
                                (res.data.current_page || context.state.page) +
                                1;
                            context.state.hasMorePages =
                                res.data.current_page < res.data.last_page;
                        } else {
                            // ✅ En paginación normal, usar update_clientes
                            context.commit("update_clientes", res.data);
                        }
                    },
                    (res) => {
                        console.error("getClientesNext error:", res);
                    }
                );
        },

        searchCliente: (context, vm) => {
            // ✅ Activar modo búsqueda
            context.commit("set_search_state", {
                isSearching: true,
                searchTerm: vm.search || "",
            });

            if (!vm.search || vm.search.trim() === "") {
                // ✅ Si no hay término de búsqueda, cargar clientes normales
                context.dispatch("getClientes");
                return;
            }

            axios
                .get(`api/get-clientes`, {
                    params: {
                        amount: 15,
                        page: 1,
                        search: vm.search,
                    },
                })
                .then(
                    (res) => {
                        // ✅ Usar search_clientes para reemplazar la lista
                        context.commit("search_clientes", res.data);
                    },
                    (res) => {
                        console.error("searchCliente error:", res);
                    }
                );
        },
    },
};

export default modulo_clientes;
