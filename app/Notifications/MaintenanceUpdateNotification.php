<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use App\Models\Notification as AppNotification;

class MaintenanceUpdateNotification
{
    public static function send(MaintenanceRequest $request, string $note): void
    {
        if ($request->tenant?->user_id) {
            AppNotification::create([
                'user_id' => $request->tenant->user_id,
                'type'    => 'maintenance_update',
                'title'   => 'تحديث طلب الصيانة',
                'body'    => "تحديث على طلب الصيانة \"{$request->title}\": {$note}",
                'data'    => ['maintenance_id' => $request->id],
            ]);
        }
    }
}
