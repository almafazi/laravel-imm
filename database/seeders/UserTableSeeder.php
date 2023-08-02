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
        // Buat peran (role) admin
        $roleAdmin = Role::create(['name' => 'admin']);

        // Buat peran (role) finance
        $roleFinance = Role::create(['name' => 'finance']);

        // Buat peran (role) purchasing
        $rolePurchasing = Role::create(['name' => 'purchasing']);

        // Tambahkan pengguna (user) admin dan tetapkan role_id dengan nilai dari $roleAdmin->id
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gudang.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admins'),
            'remember_token' => Str::random(10),
            'role_id' => $roleAdmin->id, // Tambahkan role_id sesuai nilai dari $roleAdmin->id
        ]);

        // Assign role admin ke user admin
        $userAdmin->assignRole($roleAdmin);

        // Tambahkan pengguna (user) finance dan tetapkan role_id dengan nilai dari $roleFinance->id
        $userFinance = User::create([
            'name' => 'Finance',
            'email' => 'finance@gudang.com',
            'email_verified_at' => now(),
            'password' => Hash::make('finance'),
            'remember_token' => Str::random(10),
            'role_id' => $roleFinance->id, // Tambahkan role_id sesuai nilai dari $roleFinance->id
        ]);

        // Assign role finance ke user finance
        $userFinance->assignRole($roleFinance);

        // Tambahkan pengguna (user) purchasing dan tetapkan role_id dengan nilai dari $rolePurchasing->id
        $userPurchasing = User::create([
            'name' => 'Purchasing',
            'email' => 'purchasing@gudang.com',
            'email_verified_at' => now(),
            'password' => Hash::make('purchasing'),
            'remember_token' => Str::random(10),
            'role_id' => $rolePurchasing->id, // Tambahkan role_id sesuai nilai dari $rolePurchasing->id
        ]);

        // Assign role purchasing ke user purchasing
        $userPurchasing->assignRole($rolePurchasing);
    }
}
