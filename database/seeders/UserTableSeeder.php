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
        // $user = User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gudang.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('admin'),
        //     'remember_token' => Str::random(10),
        // ]);

        // $role = Role::create(['name' => 'admin']);

        // $user->assignRole($role);

        $user = User::create([
            'name' => 'Guest',
            'email' => 'guest@gudang.com',
            'email_verified_at' => now(),
            'password' => Hash::make('guest'),
            'remember_token' => Str::random(10),
        ]);

        $role = Role::create(['name' => 'guest']);

        $user->assignRole($role);

    }
}
