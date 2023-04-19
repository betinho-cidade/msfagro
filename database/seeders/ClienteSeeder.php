<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ClienteSeeder extends Seeder
{

    public function run()
    {
        if(DB::table('clientes')->get()->count() == 0){

            DB::table('clientes')->insert([
                [
                    'user_id' => 2,
                    'tipo' => 'AG',
                    'nome' => 'Cliente Agropecuaria',
                    'email' => 'agro@agro.com',
                    'tipo_pessoa' => 'PJ',
                    'cpf_cnpj' => '00000000000191',
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'user_id' => 3,
                    'tipo' => 'PE',
                    'nome' => 'Cliente Pecuarista',
                    'email' => 'pec@pec.com',
                    'tipo_pessoa' => 'PJ',
                    'cpf_cnpj' => '46622294000194',
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'user_id' => 4,
                    'tipo' => 'AB',
                    'nome' => 'Cliente Ambos',
                    'email' => 'ambos@ambos.com',
                    'tipo_pessoa' => 'PJ',
                    'cpf_cnpj' => '60498706000157',
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],

            ]);

        } else { echo "\e[31mTabela clientes não está vazia. "; }

    }
}
