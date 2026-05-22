<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'sub_admin', 'user'];
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role
            ]);
        }

        $admin = User::firstOrCreate(
            [
                'email' => 'admin22@yopmail.com',
            ]
            ,
            [
                'name' => 'Admin Pathania',
                'password' => Hash::make('123123123'),
            ]
        );

        $admin->assignRole('admin');
    }
}
