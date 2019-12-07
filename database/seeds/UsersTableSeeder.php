<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'Usuário Homologação', 
            'email' => 'homolog@doctorservice.com.br',
            'email_verified_at' => null,
            'password' => Hash::make('1234567890'),
            'status' => 10,
            'apelido' => 'Doc Service Homolog',
            'foto' => null,
            'ultimo_login' => null,
            'idperfil' => 1,
            'remember_token' => null,
            'ativo' => 'A',            
        ]);

        

    }
}
