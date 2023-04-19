<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RoleUserSeeder extends Seeder
{

    public function run()
    {
        if(DB::table('role_user')->get()->count() == 0){

            DB::table('role_user')->insert([
                [
                    'role_id' => 1,
                    'user_id' => 1,
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'role_id' => 2,
                    'user_id' => 2,
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'role_id' => 2,
                    'user_id' => 3,
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'role_id' => 2,
                    'user_id' => 4,
                    'status' => 'A',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        } else { echo "\e[31mTabela Role_User não está vazia. "; }
    }
}
