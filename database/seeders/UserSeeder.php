<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $pegawaiRole = Role::where('name', 'pegawai')->firstOrFail();
        $userRole = Role::where('name', 'user')->firstOrFail();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@agno.test',
            'password' => Hash::make('Admin123456'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Pegawai Agno',
            'email' => 'pegawai@agno.test',
            'password' => Hash::make('Pegawai123456'),
            'role_id' => $pegawaiRole->id,
        ]);

        User::create([
            'name' => 'User Agno',
            'email' => 'user@agno.test',
            'password' => Hash::make('User123'),
            'role_id' => $userRole->id,
        ]);
    }
}
