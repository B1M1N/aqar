<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $users = [
            [
                'name'               => 'مدير النظام',
                'email'              => 'admin@aqari.com',
                'password'           => Hash::make('password'),
                'phone'              => '0500000001',
                'preferred_language' => 'ar',
                'role'               => 'admin',
            ],
            [
                'name'               => 'مدير العقارات',
                'email'              => 'manager@aqari.com',
                'password'           => Hash::make('password'),
                'phone'              => '0500000002',
                'preferred_language' => 'ar',
                'role'               => 'manager',
            ],
            [
                'name'               => 'موظف الصيانة',
                'email'              => 'staff@aqari.com',
                'password'           => Hash::make('password'),
                'phone'              => '0500000003',
                'preferred_language' => 'ar',
                'role'               => 'staff',
            ],
            [
                'name'               => 'مستأجر تجريبي',
                'email'              => 'tenant@aqari.com',
                'password'           => Hash::make('password'),
                'phone'              => '0500000004',
                'preferred_language' => 'ar',
                'role'               => 'tenant',
            ],
            [
                'name'               => 'مالك عقارات',
                'email'              => 'user@aqari.com',
                'password'           => Hash::make('password'),
                'phone'              => '0500000005',
                'preferred_language' => 'ar',
                'role'               => 'user',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->syncRoles([$role]);
        }

        // [role:user] create a linked Tenant record for the test user account
        $testUser = User::where('email', 'user@aqari.com')->first();
        if ($testUser) {
            Tenant::updateOrCreate(
                ['national_id' => 'TEST-00005'],
                [
                    'user_id' => $testUser->id,
                    'name'    => $testUser->name,
                    'phone'   => $testUser->phone,
                    'email'   => $testUser->email,
                ]
            );
        }
    }
}
