<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> b5e14bf8e75bce48b058b99ce59bf6b687c7e563
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
=======
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
>>>>>>> b5e14bf8e75bce48b058b99ce59bf6b687c7e563
    }
}