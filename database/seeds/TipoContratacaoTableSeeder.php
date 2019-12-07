<?php

use Illuminate\Database\Seeder;

class TipoContratacaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_contratacao')->insert([
            'nome' => 'CLT',
            'ativo' => 'A',                        
        ]);
        DB::table('tipo_contratacao')->insert([
            'nome' => 'RPA',
            'ativo' => 'A',                        
        ]);
        DB::table('tipo_contratacao')->insert([
            'nome' => 'PJ',
            'ativo' => 'A',                        
        ]);
    }
}
