<?php

namespace App\Notifications;

use App\Models\Lease;
use App\Models\Notification as AppNotification;
use App\Models\User;

class LeaseExpiryNotification
{
    public static function send(Lease $lease): void
    {
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            AppNotification::create([
                'user_id' => $admin->id,
                'type'    => 'lease_expiry',
                'title'   => 'انتهاء عقد إيجار قريباً',
                'body'    => "العقد #{$lease->id} للمستأجر {$lease->tenant->name} ينتهي في " . $lease->end_date->format('Y/m/d'),
                'data'    => ['lease_id' => $lease->id],
            ]);
        }

        if ($lease->tenant?->user_id) {
            AppNotification::create([
                'user_id' => $lease->tenant->user_id,
                'type'    => 'lease_expiry',
                'title'   => 'انتهاء عقد إيجارك قريباً',
                'body'    => "عقد إيجارك ينتهي في " . $lease->end_date->format('Y/m/d') . '. يرجى التواصل مع الإدارة للتجديد.',
                'data'    => ['lease_id' => $lease->id],
            ]);
        }
    }
}
