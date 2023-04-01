<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{

    public function run()
    {

        if(DB::table('users')->get()->count() == 0){

            DB::table('users')->insert([
                [
                    'name' => 'Gestor',
                    'email' => 'gestor@msfagro.com.br',
                    'password' => bcrypt('12345678'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name' => 'Cliente',
                    'email' => 'cliente@msfagro.com.br',
                    'password' => bcrypt('12345678'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);

        } else { echo "\e[31mTabela Users não está vazia. "; }

    }
}
