<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConvenioTableSeeder::class);
        $this->call(ModuloTableSeeder::class);
        $this->call(EstadoCivilTableSeeder::class);
        $this->call(PaisTableSeeder::class);
        $this->call(EstadoTableSeeder::class);
        $this->call(TipoEnderecoTableSeeder::class);
        $this->call(TipoContatoTableSeeder::class);
        $this->call(PerfilTableSeeder::class);
        $this->call(AcessosTableSeeder::class);
        $this->call(PerfilAcessoTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EspecialidadeUsersTableSeeder::class);
        $this->call(TipoContratacaoTableSeeder::class);
        $this->call(ModalidadePagamentoTableSeeder::class);
        $this->call(VagaStatusTableSeeder::class);
        $this->call(PlantaoStatusTableSeeder::class);
        
        //$this->call(NacionalidadesTableSeeder::class);
    }
}
