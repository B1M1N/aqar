<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Notification as AppNotification;

class RentReminderNotification
{
    public static function send(Invoice $invoice): void
    {
        $tenant = $invoice->tenant;
        if (!$tenant?->user_id) return;

        AppNotification::create([
            'user_id' => $tenant->user_id,
            'type'    => 'rent_reminder',
            'title'   => 'تذكير بموعد الإيجار',
            'body'    => "موعد سداد فاتورة الإيجار {$invoice->invoice_number} بمبلغ " . number_format($invoice->amount) . ' ر.س يحل في ' . $invoice->due_date->format('Y/m/d'),
            'data'    => ['invoice_id' => $invoice->id],
        ]);
    }
}
