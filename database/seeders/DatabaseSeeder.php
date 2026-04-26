<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $users = [
            ['role' => 'admin',   'name' => 'مدير النظام',    'email' => 'admin@aqari.sa',   'phone' => '0500000001'],
            ['role' => 'manager', 'name' => 'مدير العقارات',  'email' => 'manager@aqari.sa', 'phone' => '0500000002'],
            ['role' => 'staff',   'name' => 'موظف الصيانة',   'email' => 'staff@aqari.sa',   'phone' => '0500000003'],
            ['role' => 'tenant',  'name' => 'مستأجر تجريبي', 'email' => 'tenant@aqari.sa',  'phone' => '0500000004'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'               => $data['name'],
                    'password'           => bcrypt('password'),
                    'phone'              => $data['phone'],
                    'preferred_language' => 'ar',
                    'email_verified_at'  => now(),
                ]
            );
            if (! $user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }
        }
    }
}