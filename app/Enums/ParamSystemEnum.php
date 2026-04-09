<?php

namespace App\Enums;

enum ParamSystemEnum: string
{
    case TEXT_EMAIL_PAY_REMINDER = "text_email_pay_reminder";
    case INVOICE_FOOTER = "invoice_footer";
    case ENABLE_BATCH = "enable_batch";
}
