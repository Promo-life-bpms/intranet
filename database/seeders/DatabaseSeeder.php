<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\VacationPerYear;
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
       
        
        $this->call(CategoriaSoporteSeeder::class);
        $this->call(SoportePrioritySeeder::class);
        $this->call(StatusSoporteSeeder::class);

    }
}
