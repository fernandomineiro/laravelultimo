<?php

use Illuminate\Database\Seeder;

class ConvenioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('convenio')->insert([
            'nome' => '* Por hora',
            'descricao' => 'Valores por hora.',
            'ativo' => 'A'
        ]);

        DB::table('convenio')->insert([
            'nome' => '** Todos os Convênios',
            'descricao' => 'Valores para todos os convênios.',
            'ativo' => 'A'
        ]);
    }
}
