<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(RoleSeeder::class);

        User::create([
            'name' => 'Antonio',
            'lastname' => 'Tomas',
            'email' => 'admin@admin',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => '',
        ])->assignRole('Admin');;

        User::create([
            'name' => '0scar',
            'lastname' => 'Chavez',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi',
            'remember_token' => '',
        ])->assignRole('Admin');


        User::create([
            'name' => 'Diego',
            'lastname' => 'Navarrete',
            'email' => 'empleado@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi',
            'remember_token' => '',
        ])->assignRole('Empleado');
    }
}
