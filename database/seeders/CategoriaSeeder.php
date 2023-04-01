<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategoriaSeeder extends Seeder
{

    public function run()
    {
        if(DB::table('categorias')->get()->count() == 0){

            DB::table('categorias')->insert([
                [
                    'nome' => 'Macho',
                    'segmento' => 'MG',
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'nome' => 'Fêmea',
                    'segmento' => 'MG',
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'nome' => 'Macho/Fêmea',
                    'segmento' => 'MG',
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);

        } else { echo "\e[31mTabela Categorias não está vazia. "; }

    }
}
