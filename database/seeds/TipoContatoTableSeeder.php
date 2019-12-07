<?php

use Illuminate\Database\Seeder;

class TipoContatoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('tipo_contato')->insert([
            'tipo' => 'TELEFONE',
            'ativo' => 'A',                        
        ]);

        DB::table('tipo_contato')->insert([
            'tipo' => 'EMAIL',
            'ativo' => 'A',                        
        ]);


        DB::table('tipo_contato')->insert([
            'tipo' => 'SMS',
            'ativo' => 'A',                        
        ]);
    }
}
