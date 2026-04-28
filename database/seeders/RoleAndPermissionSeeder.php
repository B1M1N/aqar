<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Properties
            'properties.view', 'properties.create', 'properties.edit', 'properties.delete',
            // Units
            'units.view', 'units.create', 'units.edit', 'units.delete',
            // Tenants
            'tenants.view', 'tenants.create', 'tenants.edit', 'tenants.delete',
            // Leases
            'leases.view', 'leases.create', 'leases.edit', 'leases.delete',
            'leases.terminate', 'leases.renew', 'leases.generate-pdf',
            // Invoices
            'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete',
            'invoices.pay', 'invoices.generate-pdf',
            // Payments
            'payments.view', 'payments.create', 'payments.delete',
            // Maintenance
            'maintenance.view', 'maintenance.create', 'maintenance.edit', 'maintenance.delete',
            'maintenance.assign', 'maintenance.update-status',
            // Notifications
            'notifications.view', 'notifications.mark-read',
            // Analytics & Reports
            'analytics.view', 'ai-predictions.view', 'reports.view',
            // Settings
            'settings.view', 'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── admin ─────────────────────────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // ── manager ───────────────────────────────────────────────────────────
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'properties.view', 'properties.create', 'properties.edit',
            'units.view', 'units.create', 'units.edit',
            'tenants.view', 'tenants.create', 'tenants.edit',
            'leases.view', 'leases.create', 'leases.edit',
            'leases.terminate', 'leases.renew', 'leases.generate-pdf',
            'invoices.view', 'invoices.create', 'invoices.edit',
            'invoices.pay', 'invoices.generate-pdf',
            'payments.view', 'payments.create',
            'maintenance.view', 'maintenance.create', 'maintenance.edit',
            'maintenance.assign', 'maintenance.update-status',
            'notifications.view', 'notifications.mark-read',
            'analytics.view', 'ai-predictions.view', 'reports.view',
            'settings.view',
        ]);

        // ── staff ─────────────────────────────────────────────────────────────
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            'maintenance.view', 'maintenance.create',
            'maintenance.update-status',
            'notifications.view', 'notifications.mark-read',
        ]);

        // ── tenant ────────────────────────────────────────────────────────────
        $tenant = Role::firstOrCreate(['name' => 'tenant']);
        $tenant->syncPermissions([
            'invoices.view',
            'maintenance.view', 'maintenance.create',
            'notifications.view', 'notifications.mark-read',
        ]);
    }
}
