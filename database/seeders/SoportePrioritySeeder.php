<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoportePrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('soporte_tiempos')->insert([
            'priority'=>'Cero',
                'time'=>'00:00:00',
            ]);

        DB::table('soporte_tiempos')->insert([
        'priority'=>'Baja',
            'time'=>'05:00:00',
        ]);

        DB::table('soporte_tiempos')->insert([
            'priority'=>'Media',
            'time'=>'03:00:00',
        ]);

        DB::table('soporte_tiempos')->insert([
            'priority'=>'Alta',
            'time'=>'01:00:00',
        ]);
    }

}

