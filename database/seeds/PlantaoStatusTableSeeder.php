<?php

use Illuminate\Database\Seeder;

class PlantaoStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plantao_status')->insert([
            'nome' => 'Aberto',
            'ativo' => 'A'
        ]);
        
        DB::table('plantao_status')->insert([
            'nome' => 'Em andamento',
            'ativo' => 'A'
        ]);

        DB::table('plantao_status')->insert([
            'nome' => 'Aguardando troca',
            'ativo' => 'A'
        ]);

        DB::table('plantao_status')->insert([
            'nome' => 'Cancelado',
            'ativo' => 'A'
        ]);

        DB::table('plantao_status')->insert([
            'nome' => 'Em disputa',
            'ativo' => 'A'
        ]);

        DB::table('plantao_status')->insert([
            'nome' => 'Concluído',
            'ativo' => 'A'
        ]);
    }
}
