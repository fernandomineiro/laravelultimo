<?php

use Illuminate\Database\Seeder;

class EstadoCivilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('estado_civil')->insert([
            'estado' => 'Solteiro(a)',
            'ativo' => 'A',
        ]); 

        DB::table('estado_civil')->insert([
            'estado' => 'Casado(a)',
            'ativo' => 'A',
        ]); 


        DB::table('estado_civil')->insert([
            'estado' => 'Divorciado(a)',
            'ativo' => 'A',
        ]);
    }
}
