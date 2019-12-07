<?php

use Illuminate\Database\Seeder;

class AcessosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acesso')->insert([
            'acesso' => 'Perfil',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('perfil.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Parametro',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('parametro.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Usuário',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('usuario.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Aviso',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('aviso.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Banco',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('banco.listar'),
        ]);
        /*
        DB::table('acesso')->insert([
            'acesso' => 'Banner',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('banner.listar'),
        ]);
        
        DB::table('acesso')->insert([
            'acesso' => 'Blacklist',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('blacklist.listar'),
        ]);
        */

        DB::table('acesso')->insert([
            'acesso' => 'Convênio',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('convenio.listar'),
        ]);
        /*
        DB::table('acesso')->insert([
            'acesso' => 'Empresa',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('empresa.listar'),
        ]);
        */
        DB::table('acesso')->insert([
            'acesso' => 'Especialidade',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('especialidade.listar'),
        ]);
        
        DB::table('acesso')->insert([
            'acesso' => 'FAQ',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('faq.listar'),
        ]);
        
        DB::table('acesso')->insert([
            'acesso' => 'Feriado',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('feriado.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Instituição',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('instituicao.listar'),
        ]);
        /*
        DB::table('acesso')->insert([
            'acesso' => 'Log',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('log.listar'),
        ]);
        
        DB::table('acesso')->insert([
            'acesso' => 'Médico',
            'ativo' => 'A',
            'idmodulo' => 3,
            'rota' => route('medico.listar'),
        ]);
        */
        DB::table('acesso')->insert([
            'acesso' => 'Operador',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('operador.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Operadora',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('operadora.listar'),
        ]);
        /*
        DB::table('acesso')->insert([
            'acesso' => 'Página',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('pagina.listar'),
        ]);
        */
        DB::table('acesso')->insert([
            'acesso' => 'Prospect',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('prospect.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Sala',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('sala.listar'),
        ]);
        
        DB::table('acesso')->insert([
            'acesso' => 'Tabela de Valores',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('tabela-valor.listar'),
        ]);
        
        DB::table('acesso')->insert([
            'acesso' => 'Unidade',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('unidade.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Vaga',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('vaga.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Plantão',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('plantao.listar'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Apresentação de FAQ',
            'ativo' => 'A',
            'idmodulo' => 1,
            'rota' => route('faq'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Apresentação de FAQ',
            'ativo' => 'A',
            'idmodulo' => 2,
            'rota' => route('faq'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Apresentação de FAQ',
            'ativo' => 'A',
            'idmodulo' => 3,
            'rota' => route('faq'),
        ]);

        DB::table('acesso')->insert([
            'acesso' => 'Apresentação de FAQ',
            'ativo' => 'A',
            'idmodulo' => 4,
            'rota' => route('faq'),
        ]);
    }
}
