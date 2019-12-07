<?php

use Illuminate\Database\Seeder;

class EspecialidadeUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('especialidade')->insert([
            'nome' => 'Clinico Geral',
            'descricao' => 'Clinico Geral',
            'ativo' => 'A',
        ]); 
        
        DB::table('especialidade')->insert([
            'nome' => 'Otorrinolaringologista',
            'descricao' => 'Otorrinolaringologista',
            'ativo' => 'A',
        ]); 

        DB::table('especialidade')->insert([
            'nome' => 'Dermatologista',
            'descricao' => 'Dermatologista',
            'ativo' => 'A',
        ]); 

    }
}
