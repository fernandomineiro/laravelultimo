<?php

use Illuminate\Database\Seeder;

class ModalidadePagamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modalidade_pagamento')->insert([
            'nome' => 'Hora',
            'ativo' => 'A'
        ]);
        
        DB::table('modalidade_pagamento')->insert([
            'nome' => 'Consulta',
            'ativo' => 'A'
        ]);
    }
}
