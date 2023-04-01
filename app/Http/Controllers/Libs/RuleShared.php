<?php

namespace App\Http\Controllers\Libs;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class RuleShared
{

    public function proximo_protoloco(String $year)
    {

        $numero = DB::select("
                            SELECT max(substring(protocolo, 1, 5)) as proximo_protocolo
                            FROM solicitacaos ");

        $proximo_protocolo = $numero[0]->proximo_protocolo ?? '00379'; // alterado de 000001 para 00380 (continuar a numeração dos protocolos já existentes na AAPOMIL)
        $protocolo = Str::padLeft($proximo_protocolo + 1, 5, '0') . '.' . $year;

        return $protocolo;
    }
}
