<?php

namespace App\Exports;

use App\Models\Movimentacao;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MovimentacaosGestaoExport implements FromQuery, WithMapping, WithHeadings
{

    use Exportable;
    
    protected $search;

    public function __construct(Array $params)
    {
        $this->search = $params;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Tipo Movimentação',
            'Segmento',
            'Produtor',
            'Empresa',
            'Item',
            'Valor',
            'Data Programada',
            'Data Pagamento'
        ];
    }

    public function map($movimentacao): array
    {
        return [
            $movimentacao->id,
            $movimentacao->cliente->nome_cliente ?? '...',
            $movimentacao->tipo_movimentacao_texto,
            $movimentacao->segmento_texto,
            $movimentacao->produtor->nome_produtor ?? '...',
            $movimentacao->empresa->nome_empresa ?? '...',
            $movimentacao->item_texto,
            $movimentacao->valor,
            $movimentacao->data_programada_formatada,
            $movimentacao->data_pagamento_formatada
            //Date::stringToExcel($movimentacao->data_programada_formatada),
            //Date::stringToExcel($movimentacao->data_pagamento_formatada),                
        ];
    }

    public function query()
    {

        $search = $this->search;


        $movimentacaos = Movimentacao::where(function($query) use ($search){
                                            if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }

                                            if($search['tipo_movimentacao']){
                                                $query->where('tipo', $search['tipo_movimentacao']);
                                            }

                                            if($search['cliente']){
                                                $query->where('cliente_id', $search['cliente']);
                                            }

                                            if($search['item_texto']){
                                                $query->where('item_texto', 'like', '%' . $search['item_texto'] . '%');
                                            }                                            

                                            if($search['data_inicio'] && $search['data_fim']){
                                                $query->where('data_programada', '>=', $search['data_inicio']);
                                                $query->where('data_programada', '<=', $search['data_fim']);
                                            } elseif($search['data_inicio']){
                                                $query->where('data_programada', '>=', $search['data_inicio']);
                                            } elseif($search['data_fim']){
                                                $query->where('data_programada', '<=', $search['data_fim']);
                                            }
                                        })
                                        //->whereYear('data_programada', $data_programada_vetor[1])
                                        ->orderBy('movimentacaos.data_programada', 'desc');
        
        return $movimentacaos;
    }


}
