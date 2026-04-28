<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Notification as AppNotification;

class PaymentReceivedNotification
{
    public static function send(Invoice $invoice): void
    {
        if ($invoice->tenant?->user_id) {
            AppNotification::create([
                'user_id' => $invoice->tenant->user_id,
                'type'    => 'payment_received',
                'title'   => 'تم استلام دفعتك',
                'body'    => "تم تسجيل دفعة لفاتورة {$invoice->invoice_number} بمبلغ " . number_format($invoice->amount) . ' ر.س',
                'data'    => ['invoice_id' => $invoice->id],
            ]);
        }
    }
}
