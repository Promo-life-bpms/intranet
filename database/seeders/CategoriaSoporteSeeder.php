<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSoporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('soporte_categorias')->insert([
            'name'=>'Bpms',
            'status'=>true,
            'slug'=>Str::slug('Bpms','-')
        ]);
        DB::table('soporte_categorias')->insert([
            'name'=>'Intranet',
            'status'=>true,
            'slug'=>Str::slug('Intranet','-')
        ]);
        DB::table('soporte_categorias')->insert([
            'name'=>'Cotizador',
            'status'=>true,
            'slug'=>Str::slug('Cotizador','-')
        ]);
        DB::table('soporte_categorias')->insert([
            'name'=>'Promo Connected',
            'status'=>true,
            'slug'=>Str::slug('Promo Connected','-')
        ]);
        DB::table('soporte_categorias')->insert([
            'name'=>'Diseño de tickets',
            'status'=>true,
            'slug'=>Str::slug('Diseño de tickets','-')
        ]);
    }
}
