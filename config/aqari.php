<?php

return [
    'app_name'                => 'عقاري',
    'auto_invoices'           => true,
    'late_invoice_updates'    => true,
    'rent_reminders'          => true,
    'lease_expiry_notify'     => true,
    'monthly_reports'         => true,
    'ai_predictions'          => true,
    'rent_reminder_days'      => 5,
    'lease_expiry_days'       => 30,
    'moyasar_enabled'         => false,
    'moyasar_secret_key'      => env('MOYASAR_SECRET_KEY', ''),
    'moyasar_publishable_key' => env('MOYASAR_PUBLISHABLE_KEY', ''),
];
