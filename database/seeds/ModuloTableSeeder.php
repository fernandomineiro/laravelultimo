<?php

use Illuminate\Database\Seeder;

class ModuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('modulo')->insert([
            'nome' => 'Doctor Service',
            'ativo' => 'A',            
        ]);

        DB::table('modulo')->insert([
            'nome' => 'Operadora',
            'ativo' => 'A',            
        ]);

        DB::table('modulo')->insert([
            'nome' => 'Médico',
            'ativo' => 'A',            
        ]);

        DB::table('modulo')->insert([
            'nome' => 'Web',
            'ativo' => 'A',           
        ]);
    }
}
