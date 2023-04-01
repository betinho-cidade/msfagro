<?php

namespace App\Exports;

use App\Models\Filiado;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class FiliadosExport implements FromQuery, WithMapping, WithHeadings
{

    protected $params;

    public function __construct(Array $params)
    {
        $this->params = $params;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Categoria',
            'Nome',
            'Nome Guerra',
            'E-mail',
            'Data Nascimento',
            'CPF',
            'RG',
            'Telefone Celular',
            'Telefone Residencial',
            'CEP',
            'Cidade',
            'UF',
            'Endereço',
            'Número',
            'Bairro',
            'Complemento'
        ];
    }

    public function map($filiado): array
    {

        return [
            ($filiado->filiado_id == null) ? $filiado->id : $filiado->filiado_id,
            $filiado->categoria->nome,
            $filiado->user->name,
            $filiado->nome_guerra,
            $filiado->user->email,
            Date::stringToExcel($filiado->data_nascimento),
            $filiado->cpf,
            $filiado->rg,
            $filiado->tel_celular,
            $filiado->tel_residencial,
            $filiado->end_cep,
            $filiado->end_cidade,
            $filiado->end_uf,
            $filiado->end_logradouro,
            $filiado->end_numero,
            $filiado->end_bairro,
            $filiado->end_complemento,
        ];
    }

    public function query()
    {

        $excel_params = $this->params;

        $filiados = Filiado::join('admissaos', 'filiados.id', '=', 'admissaos.filiado_id')
                    ->where('admissaos.id', '=', DB::raw('(SELECT max(I.id) FROM admissaos I WHERE I.filiado_id = admissaos.filiado_id)'))
                    ->Where(function($query) use ($excel_params){

                        if ($excel_params['dt_inicial'] && $excel_params['dt_final']) {

                            //$dt_ini = implode('/',array_reverse(explode('/',$excel_params['dt_inicial'])));
                            //$dt_fim = implode('/',array_reverse(explode('/',$excel_params['dt_final'])));

                            $dt_ini = str_replace("-", "", $excel_params['dt_inicial']);
                            $dt_fim = str_replace("-", "", $excel_params['dt_final']);

                            $query->where('admissaos.data_admissao', '>=', $dt_ini);
                            $query->where('admissaos.data_desligamento', '<=', $dt_fim);
                        }

                        if($excel_params['id_filter']){
                            $query->where('filiados.id', '=', $excel_params['id_filter']);
                        }

                        if($excel_params['name_filter']){
                            $users = User::where('name', 'like', $excel_params['name_filter'])
                                        ->pluck('id');
                            $query->whereIn('filiados.user_id', $users);
                        }

                        if($excel_params['categoria_filter']){
                            $query->where('categoria_id', '=', $excel_params['categoria_filter']);
                        }

                        if($excel_params['ativo_filter']){
                            $query->where('admissaos.status', $excel_params['ativo_filter']);
                        }

                        if($excel_params['formapagamento_filter']){
                            $query->where('admissaos.forma_pagamento', $excel_params['formapagamento_filter']);
                        }

                        if($excel_params['debitoautomatico_filter']){
                            $query->where('admissaos.situacao_debito_automatico', $excel_params['debitoautomatico_filter']);
                        }

                        if($excel_params['carencia_filter']){

                            if($excel_params['carencia_filter'] == 'S'){
                                $query->where('admissaos.carencia', 'N');

                            } else if ($excel_params['carencia_filter'] == 'A') {
                                $query->where('admissaos.data_termino_carencia', '>', Carbon::now());

                            } else if ($excel_params['carencia_filter'] == 'I') {
                                $query->where('admissaos.data_termino_carencia', '<', Carbon::now());

                            }
                        }
                    })
                    ->select('filiados.*')
                    ->orderBy('filiados.id', 'desc');

        return $filiados;
    }


}
