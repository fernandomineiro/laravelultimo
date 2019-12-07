<?php

use Illuminate\Database\Seeder;

class TipoEnderecoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Este tipo endereço é temporario e necessita de ajustes visto estar sendo usado no código fonte 
        DB::table('tipo_endereco')->insert([
            'tipo' => 'OPERADORA',
            'ativo' => 'A',                        
        ]);

        DB::table('tipo_endereco')->insert([
            'tipo' => 'RESIDENCIAL',
            'ativo' => 'A',                        
        ]);


        DB::table('tipo_endereco')->insert([
            'tipo' => 'COMERCIAL',
            'ativo' => 'A',                        
        ]);
    }
}
