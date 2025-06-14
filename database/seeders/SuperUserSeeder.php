<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'Administrator',
            'email' => 'superadmin@gmail.com',
        ],
        [
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $user->assignRole($superAdmin);
    }
}
