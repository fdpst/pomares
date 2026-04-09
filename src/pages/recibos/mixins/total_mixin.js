export const total_mixin = {
    methods: {
        calcularTotales(lista_servicios) {
            let sub_total = lista_servicios.reduce((acc, servicio) => {
                let importe = isNaN(parseEuroNumber(servicio.importe))
                    ? 0
                    : parseEuroNumber(servicio.importe);
                return acc + importe;
            }, 0);

            let tipo_descuento = this.recibo.tipo_descuento;
            let descuento = this.recibo.descuento;
            let total_descuento = 0;

            if (tipo_descuento == 0 && descuento > 0) {
                total_descuento = 0;
                total_descuento = (sub_total * descuento) / 100;
            } else if (tipo_descuento == 1 && descuento > 0) {
                total_descuento = 0;
                total_descuento = descuento;
            } else {
                total_descuento = 0;
            }

            this.recibo.IVAs = this.calcIVAs(lista_servicios);

            let acc = 0;
            Object.keys(this.recibo.IVAs ?? {}).forEach((key) => {
                acc += parseFloat(this.recibo.IVAs[key]);
            });
            let iva = parseFloat(acc).toFixed(2);
            this.calcImporteIvaByServicio();
            /*this.recibo.has_iva == false || this.tipo == "nota"
                    ? 0
                    : parseFloat(
                          ((sub_total - total_descuento) *
                              this.recibo.tipo_iva) /
                              100
                      ).toFixed(2);*/

            let total =
                parseFloat(sub_total) +
                parseFloat(iva) -
                parseFloat(total_descuento);
            this.recibo.sub_total = sub_total;
            this.recibo.iva = iva;
            this.recibo.total_descuento =
                parseFloat(total_descuento).toFixed(2);
            this.recibo.total = parseFloat(total).toFixed(2);
        },
        calcImporteIvaByServicio() {
            if (this.recibo.servicios.length < 0) return;
            for (let i = 0; i < this.recibo.servicios.length; i++) {
                this.recibo.servicios[i].importe_iva =
                    this.calcIVA(this.recibo.servicios[i]) ?? 0;
            }
            // console.log(this.recibo.servicios, " servicios de la factura");
        },
        /**
         * @param {Array} servicios
         */
        calcIVAs(servicios) {
            /**
             * @var {Object} IVAs
             */
            const IVAs = {};
            for (const servicio of servicios) {
                const tipo_iva =
                    servicio.iva_percent != null ? servicio.iva_percent : 0;

                const iva_producto =
                    (parseEuroNumber(servicio.importe) * tipo_iva) / 100;

                // Verificar si ya existe una entrada para este tipo de IVA en el arreglo $ivas
                if (IVAs.hasOwnProperty(tipo_iva)) {
                    // Si ya existe, sumar el IVA actual al valor existente
                    IVAs[tipo_iva] += iva_producto;
                } else {
                    // Si no existe, agregar una nueva entrada con el IVA actual
                    IVAs[tipo_iva] = iva_producto;
                }
                /*if (IVAs.hasOwnProperty(servicio.iva_percent)) {
                    IVAs[servicio.iva_percent] =
                        IVAs[servicio.iva_percent] ??
                        0 + this.calcIVA(servicio) ??
                        0;
                    continue;
                }
                IVAs[servicio.iva_percent] = this.calcIVA(servicio) ?? 0;*/
            }
            return IVAs;
        },
        /**
         * @param {Object} servicio
         */
        calcIVA(servicio) {
            const _n = parseFloat;
            return (_n(servicio.iva_percent ?? 0) * _n(servicio.importe)) / 100;
            /*return (
                (_n(servicio.iva_percent ?? 0) *
                    _n(this.cleanNumber(servicio.importe))) /
                100
            );*/
        },
        cleanNumber(n) {
            return String(n).replaceAll(".", "").replaceAll(",", ".");
        },
    },
};
