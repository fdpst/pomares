export default {
    data() {
        return {
            document_number: "",
            labelByType: {
                presupuesto: "Nro. Presupuesto",
                factura: "Nro. Factura",
                facturarectificativa: "Nro. Factura Rectificativa",
                facturaproforma: "Nro. Factura Proforma",
                "parte-trabajo": "Nro. Parte de Trabajo",
                nota: "Nro. Albarán",
            },
        };
    },
    methods: {
        loadDocumentNumber() {
            this.document_number = this.getDocumentNumber();
        },
        getDocumentNumber() {
            switch (this.tipo) {
                case "presupuesto":
                    return this.recibo.nro_presupuesto.nro_presupuesto;
                case "factura":
                    return this.recibo.nro_factura?.nro_factura;
                case "facturarectificativa":
                    return this.recibo.nro_factura_rectificativa?.nro_factura;
                case "facturaproforma":
                    return this.recibo.nro_factura_prof?.nro_factura_prof;
                case "parte-trabajo":
                    return this.recibo.nro_parte_trabajo?.nro_parte_trabajo;
                case "nota":
                    return this.recibo.nro_nota?.nro_nota;
                default:
                    throw new Error("cannot handle document number");
            }
        },
    },
};
