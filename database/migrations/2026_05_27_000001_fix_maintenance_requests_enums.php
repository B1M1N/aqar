<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing data to the new enum values
        DB::statement("UPDATE `maintenance_requests` SET `type` = CASE
            WHEN `type` = 'ac'       THEN 'hvac'
            WHEN `type` = 'painting' THEN 'other'
            ELSE `type` END");

        DB::statement("UPDATE `maintenance_requests` SET `status` = CASE
            WHEN `status` = 'pending'   THEN 'open'
            WHEN `status` = 'assigned'  THEN 'in_progress'
            WHEN `status` = 'completed' THEN 'resolved'
            ELSE `status` END");

        DB::statement("ALTER TABLE `maintenance_requests`
            MODIFY COLUMN `type`   ENUM('electrical','plumbing','hvac','structural','appliance','other') NOT NULL,
            MODIFY COLUMN `status` ENUM('open','in_progress','resolved','cancelled')                     NOT NULL DEFAULT 'open'");
    }

    public function down(): void
    {
        DB::statement("UPDATE `maintenance_requests` SET `type` = CASE
            WHEN `type` = 'hvac'       THEN 'ac'
            WHEN `type` = 'structural' THEN 'other'
            WHEN `type` = 'appliance'  THEN 'other'
            ELSE `type` END");

        DB::statement("UPDATE `maintenance_requests` SET `status` = CASE
            WHEN `status` = 'open'     THEN 'pending'
            WHEN `status` = 'resolved' THEN 'completed'
            ELSE `status` END");

        DB::statement("ALTER TABLE `maintenance_requests`
            MODIFY COLUMN `type`   ENUM('plumbing','electrical','ac','painting','other')                          NOT NULL,
            MODIFY COLUMN `status` ENUM('pending','assigned','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
