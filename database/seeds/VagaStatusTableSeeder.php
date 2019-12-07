<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VagaStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('vaga_status')->insert([
            'nome' => 'Sem candidatos',
            'ativo' => 'A'
        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Com candidatos',
            'ativo' => 'A'
        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Vaga preenchida',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Atrasada',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Em andamento',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Vaga finalizada',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Em disputa',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Candidato escolhido',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Trocar a vaga',
            'ativo' => 'A'
        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Trocar o plantão',
            'ativo' => 'A'

        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Candidato confirmado',
            'ativo' => 'A'
        ]);

        DB::table('vaga_status')->insert([
            'nome' => 'Candidato disponível',
            'ativo' => 'A'

        ]);


    }
}
