<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NacionalidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create('pt_BR');

        for($i = 0; $i < 40; $i++){

            DB::table('nacionalidade')->insert([
                'nacionalidade' => substr($faker->country, 0, 45),
                'ativo' => 1
            ]);
        }

    }
}
