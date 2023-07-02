<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GooglemapSeeder extends Seeder
{

    public function run()
    {

        if(DB::table('googlemaps')->get()->count() == 0){

            DB::table('googlemaps')->insert([
                [
                    'id' => 1,
                    'valor_credito' => '200',
                    'valor_extra_apimaps' => '7',
                    'qtd_apimaps' => '21000',
                    'valor_extra_geolocation' => '5',
                    'qtd_geolocation' => '7000',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]);

        } else { echo "\e[31mTabela Googlemaps não está vazia. "; }

    }
}
