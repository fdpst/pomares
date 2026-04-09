export const servicios_mixin = {
    data() {
        return {};
    },
    methods: {
        addService() {
            /* Si el servicio a introducir esta vacio da error si no lo introduce en la lista*/
            if (this.servicio.descripcion == "") {
                return $toast.error("Introduzca: DESCRIPCION CORRECTA");
            }
            if (
                this.servicio.cantidad === undefined ||
                this.servicio.cantidad === "" ||
                this.servicio.cantidad === null
            ) {
                this.servicio.cantidad = 1;
            }
            if (
                this.servicio.precio === undefined ||
                this.servicio.precio === "" ||
                this.servicio.precio === null
            ) {
                this.servicio.precio = 0;
            }
            if (
                this.servicio.iva_percent === undefined ||
                this.servicio.iva_percent === null ||
                this.servicio.iva_percent === ""
            ) {
                this.servicio.iva_percent = 0;
            }
            if (
                this.servicio.lote === undefined ||
                this.servicio.lote === null
            ) {
                this.servicio.lote = "";
            }
            /* Es de tipo albaran*/
            if (this.servicio.descripcion.substr(0, 7) == "Albarán") {
                this.recibo.servicios.push(this.servicio);
                this.calcularTotales(this.recibo.servicios);

                this.resetServicio();
                $toast.sucs("Albaran Añadido");
            } else if (
                /*Es de tipo manual*/
                this.servicio.descripcion.substr(0, 7) != "Albarán"
            ) {
                this.updateOrPush(this.servicio);
                this.calcularTotales(this.recibo.servicios);

                this.resetServicio();
                $toast.sucs("Servicio Añadido");
            } else {
                $toast.error("Error");
            }
        },

        updateOrPush(servicio) {
            let index = this.recibo.servicios.findIndex(
                (element) => element.id == servicio.id
            );

            if (index > -1) {
                this.recibo.servicios.splice(index, 1, servicio);
            } else {
                this.recibo.servicios.push(servicio);
            }
        },

        obtenerServicio(checkbox) {
            for (let index = 0; index < this.recibo.servicios.length; index++) {
                let albaranEliminar = "Albaran_" + checkbox;
                if (
                    this.recibo.servicios[index].descripcion == albaranEliminar
                ) {
                    return this.recibo.servicios[index];
                }
            }
        },

        deleteItem(servicio, checkbox) {
            // Verificamos si el elemento a elminar es temporal o esta en BD
            let tipo = String(servicio.id).substr(0, 1);
            // Si es temporal lo eliminamos
            if (tipo == "t") {
                if (servicio) {
                    let data = servicio.descripcion.substr(8, 5);
                    for (let index = 0; index < checkbox.length; index++) {
                        if (data == checkbox[index]) {
                            checkbox[index] = undefined;
                        }
                    }
                }
                //  Eliminamos el servicio de la lista de servicios a facturar
                let index = this.recibo.servicios.indexOf(servicio);
                this.recibo.servicios.splice(index, 1);
                // Restauramos los campos de datos de servicio para poder almacenar mas
                this.resetServicio();
                // Calculamos los totales de la factura
                this.calcularTotales(this.recibo.servicios);
            }

            // Si no es temporal realizamos cambios en BD
            else {
                if (servicio) {
                    let data = servicio.descripcion.substr(8, 5);
                    for (let index = 0; index < checkbox.length; index++) {
                        if (data == checkbox[index]) {
                            checkbox[index] = undefined;
                        }
                    }
                }
                //  Eliminamos el servicio de la lista de servicios a facturar
                let index = this.recibo.servicios.indexOf(servicio);
                this.recibo.servicios.splice(index, 1);
                // Restauramos los campos de datos de servicio para poder almacenar mas
                this.resetServicio();
                // Calculamos los totales de la factura
                this.calcularTotales(this.recibo.servicios);
                // Almacenamos el servicio nombre para comprobar si es albaran
                // let elementoname = servicio.descripcion.substr(0, 7)
                let elementoname = servicio.descripcion;
                // Enviamos para la eliminacion el nombre y las id de servicio y de recibo
                this.removeContabilizado(
                    elementoname,
                    servicio.id,
                    this.recibo.id
                );
            }
        },

        removeContabilizado(elemento, idServicio, idRecibo) {
            axios
                .get(
                    `api/remove-contabilizado/${elemento}/${idServicio}/${idRecibo}`
                )
                .then(
                    (res) => {
                        // almacenamos el resultado de la eliminacion y lo mostramos en el listado
                        console.log(res.data);
                        let resultado = res.data;
                        //  Si eliminamos todos los servicios mostramos la linea nula
                        if (this.recibo.servicios.length == 0) {
                            this.recibo.servicios.push(resultado);
                        }
                        $toast.sucs("Actualizando datos ....");
                        // Almacenamos la factura y creamos el pdf y refrescamos listado albaranes disponibles
                        this.saveFactura();
                        //this.dataGet();
                    },
                    (res) => {
                        $toast.error("Error eliminando estado contabilizado");
                    }
                );
        },

        setItem(servicio) {
            this.servicio = JSON.parse(JSON.stringify(servicio));
            if (
                this.servicio.iva_percent === undefined ||
                this.servicio.iva_percent === null
            ) {
                this.servicio.iva_percent = 0;
            }
            if (
                this.servicio.lote === undefined ||
                this.servicio.lote === null
            ) {
                this.servicio.lote = "";
            }
            if (
                this.servicio.cantidad === undefined ||
                this.servicio.cantidad === null ||
                this.servicio.cantidad === ""
            ) {
                this.servicio.cantidad = 1;
            }
            if (
                this.servicio.precio === undefined ||
                this.servicio.precio === null ||
                this.servicio.precio === ""
            ) {
                this.servicio.precio = 0;
            }
            if (servicio.descripcion.substr(0, 7) == "Albaran") {
                this.setAlbaran();
            }
        },

        isAlbaran(item) {
            return (
                String(item.descripcion.substr(0, 7)).toLowerCase() == "albaran"
            );
        },

        setAlbaran() {
            this.addService();
        },

        resetServicio() {
            this.servicio = {
                recibo_id: null,
                id: `temp-${new Date().getTime()}`,
                descripcion: "",
                cantidad: 1,
                precio: 0,
                id_servicio: null,
                importe: "",
                iva_percent: 0,
                lote: "",
            };
        },
    },
    computed: {
        headers() {
            const includeLote = !!this.batchEnabled;

            const prependLote = (columns) => {
                if (!includeLote) return columns;

                return [
                    {
                        title: "Lote",
                        align: "left",
                        value: "lote",
                    },
                    ...columns,
                ];
            };

            if (this.tipo != "nota" && this.tipo != "presupuesto") {
                return prependLote([
                    {
                        title: "Servicio/Articulo",
                        align: "left",
                        value: "id_servicio",
                    },
                    {
                        title: "Descripción",
                        align: "left",
                        value: "descripcion",
                    },
                    {
                        title: "Cantidad",
                        value: "cantidad",
                    },
                    {
                        title: "Precio",
                        value: "precio",
                    },
                    {
                        title: "I.V.A.",
                        align: "left",
                        value: "iva_percent",
                    },
                    {
                        title: "Importe",
                        value: "importe",
                    },
                    {
                        title: "Acciones",
                        value: "action",
                        sortable: false,
                    },
                ]);
            }

            return prependLote([
                {
                    title: "Servicio/Articulo",
                    align: "left",
                    value: "id_servicio",
                },
                {
                    title: "Descripción",
                    align: "left",
                    value: "descripcion",
                },
                {
                    title: "Cantidad",
                    value: "cantidad",
                },
                {
                    title: "Precio",
                    value: "precio",
                },
                {
                    title: "Importe",
                    value: "importe",
                },
                {
                    title: "Acciones",
                    value: "action",
                    sortable: false,
                },
            ]);
        },
    },
};
