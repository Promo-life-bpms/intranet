<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSoporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('soporte_status')->insert([
            'name'=>'Creado',
            'slug'=>Str::slug('Creado','-')
        ]);
        DB::table('soporte_status')->insert([
            'name'=>'En proceso',
            'slug'=>Str::slug('En proceso','-')
        ]);
        DB::table('soporte_status')->insert([
            'name'=>'Resuelto',
            'slug'=>Str::slug('Resuelto','-')
        ]);

        
    }
}
