<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $all = [
            'dashboard.view',
            'properties.viewAny', 'properties.view', 'properties.create', 'properties.update', 'properties.delete',
            'units.viewAny', 'units.view', 'units.create', 'units.update', 'units.delete',
            'tenants.viewAny', 'tenants.view', 'tenants.create', 'tenants.update', 'tenants.delete',
            'leases.viewAny', 'leases.view', 'leases.create', 'leases.update', 'leases.delete',
            'leases.renew', 'leases.terminate', 'leases.pdf',
            'invoices.viewAny', 'invoices.view', 'invoices.create', 'invoices.update',
            'invoices.delete', 'invoices.pay', 'invoices.pdf',
            'payments.viewAny', 'payments.view', 'payments.create',
            'maintenance.viewAny', 'maintenance.view', 'maintenance.create',
            'maintenance.update', 'maintenance.delete', 'maintenance.assign', 'maintenance.updateStatus',
            'analytics.view', 'ai.view', 'reports.view',
            'notifications.view', 'notifications.markRead',
            'settings.view', 'settings.update',
        ];

        foreach ($all as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'dashboard.view',
            'properties.viewAny', 'properties.view', 'properties.create', 'properties.update', 'properties.delete',
            'units.viewAny', 'units.view', 'units.create', 'units.update', 'units.delete',
            'tenants.viewAny', 'tenants.view', 'tenants.create', 'tenants.update', 'tenants.delete',
            'leases.viewAny', 'leases.view', 'leases.create', 'leases.update', 'leases.delete',
            'leases.renew', 'leases.terminate', 'leases.pdf',
            'invoices.viewAny', 'invoices.view', 'invoices.create', 'invoices.update',
            'invoices.delete', 'invoices.pay', 'invoices.pdf',
            'payments.viewAny', 'payments.view', 'payments.create',
            'maintenance.viewAny', 'maintenance.view', 'maintenance.create',
            'maintenance.update', 'maintenance.assign', 'maintenance.updateStatus',
            'analytics.view', 'ai.view', 'reports.view',
            'notifications.view', 'notifications.markRead',
            'settings.view',
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'dashboard.view',
            'maintenance.viewAny', 'maintenance.view', 'maintenance.create',
            'maintenance.update', 'maintenance.updateStatus',
            'notifications.view', 'notifications.markRead',
        ]);

        $tenant = Role::firstOrCreate(['name' => 'tenant', 'guard_name' => 'web']);
        $tenant->syncPermissions([
            'invoices.viewAny', 'invoices.view',
            'maintenance.viewAny', 'maintenance.view', 'maintenance.create',
            'notifications.view', 'notifications.markRead',
        ]);
    }
}