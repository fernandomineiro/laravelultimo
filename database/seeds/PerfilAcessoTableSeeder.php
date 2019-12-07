<?php

use Illuminate\Database\Seeder;

class PerfilAcessoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('perfil_acesso')->insert([
            'visualizacao' => 1,
            'cadastro' => 1,
            'edicao' => 1,
            'exclusao' => 1,
            'ativo' => 'A',
            'idacesso' => 1,
            'idperfil' => 1,
        ]);


        DB::table('perfil_acesso')->insert([
            'visualizacao' => 1,
            'cadastro' => 1,
            'edicao' => 1,
            'exclusao' => 1,
            'ativo' => 'A',
            'idacesso' => 2,
            'idperfil' => 1,
        ]);
    }
}
