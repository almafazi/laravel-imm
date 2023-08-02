<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gudang.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admins'),
            'remember_token' => Str::random(10),
            'role_id' => $role->id
        ]);

        $user->assignRole($role);
    }
}
