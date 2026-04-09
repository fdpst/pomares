<?php

namespace App\Enums;

enum PayloadElectronicInvoicingKeys: string
{
    case CUSTOMER_ID = "cliente_id";
    case DATE = "fecha";
    case DATE_END = "fecha_tope";
    case SUBTOTAL = "sub_total";
    case IVA = "iva";
    case TYPE_IVA = "tipo_iva";
    case DISCOUNT_TYPE = "tipo_descuento";
    case DISCOUNT = "descuento";
    case DISCOUNT_TOTAL = "total_descuento";
    case TOTAL = "total";
    case BUDGET_URL = "presupuesto_url";
    case INVOICE_URL = "factura_url";
    case NOTE_URL = "nota_url";
    case ORDER_URL = "orden_url";
    case HAS_IVA = "has_iva";
    case USER_ID = "user_id";
    case OBSERVATIONS = "observaciones";
    case PAYMENT_METHOD = "metodo_pago";
    case PAYMENT_DETAIL = "detalle_pago";
    case PAID = "pagado";
    case RECURRENT = "recurrente";
}
