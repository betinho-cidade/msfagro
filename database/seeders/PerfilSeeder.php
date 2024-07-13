<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilSeeder extends Seeder
{

    public function run()
    {
        if(DB::table('perfils')->get()->count() == 0){

            DB::table('perfils')->insert([
                [
                    'id' => 1,
                    'name' => 'Master',
                    'description' => 'Master do Cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    'name' => 'Gerente',
                    'description' => 'Gerente do Cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],     
                [
                    'id' => 3,
                    'name' => 'Administrativo',
                    'description' => 'Administrativo do Cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],                              
                [
                    'id' => 4,
                    'name' => 'Tecnico',
                    'description' => 'Técnico do Cliente',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],                     
            ]);

        } else { echo "\e[31mTabela Perfils não está vazia. "; }

    }

}
