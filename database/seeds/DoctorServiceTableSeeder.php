<?php

use Illuminate\Database\Seeder;

class DoctorServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        DB::table('nacionalidade')->insert([
            'nacionalidade' => 'brasileiro',
            'ativo' => 'A',            
        ]); 
        
        DB::table('pessoa')->insert([
            'tipo' => 'PF',            
            'ativo' => 'A',
        ]); 

        DB::table('pessoa_fisica')->insert([
            'nome' => 'Usuário Homologação',
            'cpf' => '21998731330', 
            'idpessoa' => 1,
            'idestado_civil' => 1,
            'rg' => null,
            'ativo' => 'A',
            'sexo' => 'M',
            'idnacionalidade' => 41,
        ]); 

        DB::table('doctor_service')->insert([
            'idpessoa' => 1,
            'iduser' => 1,            
            'ativo' => 'A',
        ]); 
    }
}
