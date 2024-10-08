<?php

namespace App\Exports;

use App\Models\Movimentacao;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;


class MovimentacaosExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithEvents
{

    use Exportable;

    protected $search, $contHeader, $contRows, $contDespesa, $contReceita, $resultado;

    public function __construct(Array $params)
    {
        if(Gate::denies('view_relatorio')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $this->search = $params;
        $this->contHeader = 1;
        $this->contRows = 0;
        $this->contDespesa = 0;
        $this->contReceita = 0;
        $this->resultado = 0;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Data Programada',
            'Data Pagamento',
            'Categoria',
            'Tipo Movimentação',
            'Valor',
            'Segmento',
            'Produtor',
            'Qtd.Machos',
            'Qtd.Fêmeas',
            'Empresa',
            'Item',
            'Forma Pagamento',
            'Número Nota',
            'Link Nota',
            'Link Comprovante',
            'Link Anexo',
            'Link GTA',
            'Observacao',
        ];
    }

    public function map($movimentacao): array
    {
        if(!($movimentacao instanceof Movimentacao) && isset($movimentacao['is_summary']) && $movimentacao['is_summary']===true){
            return [
                [],
                ['','','','','Total Despesa', $movimentacao['total_despesa']],
                ['','','','','Total Receita', $movimentacao['total_receita'],'','Mov: Machos/Fêmeas', $movimentacao['total_machos'], $movimentacao['total_femeas']],
                [],
                ['','','','','Resultado', $movimentacao['total_geral'],'','Total Bovinos', $movimentacao['total_bovinos']],
            ];
          }else{
            if ($movimentacao instanceof Movimentacao){
                return [
                    $movimentacao->id,
                    Date::stringToExcel($movimentacao->data_programada_formatada),
                    Date::stringToExcel($movimentacao->data_pagamento_formatada),
                    $movimentacao->categoria->nome,
                    $movimentacao->tipo_movimentacao_texto,
                    $movimentacao->valor ?? 0,
                    $movimentacao->segmento_texto,
                    $movimentacao->produtor->nome_produtor ?? '...',
                    ($movimentacao->segmento == 'MG' && ($movimentacao->efetivo->tipo == 'CP' || $movimentacao->efetivo->tipo == 'VD')) ? (($movimentacao->efetivo->qtd_macho) ? $movimentacao->efetivo->qtd_macho : ' ') : ' ',
                    ($movimentacao->segmento == 'MG' && ($movimentacao->efetivo->tipo == 'CP' || $movimentacao->efetivo->tipo == 'VD')) ? (($movimentacao->efetivo->qtd_femea) ? $movimentacao->efetivo->qtd_femea : ' ') : ' ',
                    $movimentacao->empresa->nome_empresa ?? '...',
                    $movimentacao->item_texto,
                    $movimentacao->forma_pagamento->forma,
                    $movimentacao->nota,
                    $movimentacao->link_nota_guest,
                    $movimentacao->link_comprovante_guest,
                    $movimentacao->link_anexo_guest,
                    ($movimentacao->efetivo) ? $movimentacao->efetivo->link_gta_guest : ' ',
                    ($movimentacao->efetivo) ? ($movimentacao->efetivo->observacao ?? ' ') : ($movimentacao->observacao ?? ' ')
                ];
            }
          }
    }

    public function query()
    {

        $search = $this->search;

        $user = Auth()->User();

        $dados = Movimentacao::where('movimentacaos.cliente_id', $user->cliente_user->cliente->id)
                                        ->where(function($query) use ($search){
                                            if($search['tipo_cliente'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }

                                            if($search['tipo_movimentacao']){
                                                $query->where('tipo', $search['tipo_movimentacao']);
                                            }

                                            if($search['produtor']){
                                                $query->where('produtor_id', $search['produtor']);
                                            }

                                            if($search['empresa']){
                                                $query->where('empresa_id', $search['empresa']);
                                            }

                                            if($search['forma_pagamento']){
                                                $query->where('forma_pagamento_id', $search['forma_pagamento']);
                                            }

                                            if($search['item_texto']){
                                                $query->where('item_texto', 'like', '%' . $search['item_texto'] . '%');
                                            }

                                            if($search['nota']){
                                                $query->where('nota', 'like', '%' . $search['nota'] . '%');
                                            }

                                            if($search['data_inicio'] && $search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    }
                                                } else {
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                }
                                            } elseif($search['data_inicio']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                }
                                            } elseif($search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                }
                                            } else {
                                                if($search['movimentacao'] == 'F'){
                                                    $query->whereNull('data_pagamento');
                                                }else if($search['movimentacao'] == 'E'){
                                                    $query->whereNotNull('data_pagamento');
                                                }
                                            }
                                        })
                                        ->orderBy('tipo', 'desc'); // primeiro por Despesa, depois por Receita

        if($search['data_inicio'] && $search['data_fim']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc');
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc');
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                }
            } else {
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
            }
        } elseif($search['data_inicio']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc');
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc');
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                }
            } else{
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
            }
        } elseif($search['data_fim']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc');
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc');
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                }
            } else {
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
            }
        } else {
            if($search['movimentacao'] == 'F'){
                $movimentacaos = $dados->orderBy('data_programada', 'desc');
            }else if($search['movimentacao'] == 'E'){
                $movimentacaos = $dados->orderBy('data_pagamento', 'desc');
            }else if($search['movimentacao'] == 'G'){
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
            }
        }

        return $movimentacaos;
    }

    public function prepareRows($movimentacaos){

        $total_despesa = 0;
        $total_receita = 0;
        $total_geral = 0;

        $total_machos = 0;
        $total_femeas = 0;

        foreach($movimentacaos as $row){

            $this->contRows += 1;

            if($row->tipo == 'D'){
                $total_despesa+=$row->valor;
                $this->contDespesa += 1;
            }

            if($row->tipo == 'R'){
                $total_receita+=$row->valor;
                $this->contReceita += 1;
            }

            if($row->segmento == 'MG'){
                if($row->efetivo->tipo == 'CP'){
                    $total_machos = $total_machos + ($row->efetivo->qtd_macho ?? 0);
                    $total_femeas = $total_femeas + ($row->efetivo->qtd_femea ?? 0);
                } else if($row->efetivo->tipo == 'VD'){
                    $total_machos = $total_machos - ($row->efetivo->qtd_macho ?? 0);
                    $total_femeas = $total_femeas - ($row->efetivo->qtd_femea ?? 0);
                }
            }
        }

        $movimentacaos[]=[
          'is_summary'=>true,
          'total_despesa'=>$total_despesa,
          'total_receita'=>$total_receita,
          'total_geral'=> ($total_receita - $total_despesa),
          'total_machos'=>$total_machos,
          'total_femeas'=>$total_femeas,
          'total_bovinos'=>($total_machos+ $total_femeas),
        ];

        $this->resultado = $total_receita - $total_despesa;

        return $movimentacaos;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $cel_resultado = 'E' . ($this->contHeader + $this->contRows + 5);
        $celula_valor  = 'F' . ($this->contHeader + $this->contRows + 5);

        $celula_texto  = 'H' . ($this->contHeader + $this->contRows + 3);
        $celula_macho  = 'I' . ($this->contHeader + $this->contRows + 3);
        $celula_femea  = 'J' . ($this->contHeader + $this->contRows + 3);

        $cel_quantidade = 'H' . ($this->contHeader + $this->contRows + 5);
        $celula_total  = 'I' . ($this->contHeader + $this->contRows + 5);

        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true],
                     'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'C' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'E' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'G' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'I' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'J' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            $cel_resultado => ['font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $celula_valor => ['font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
                    $celula_texto => ['font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $celula_macho => ['font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $celula_femea => ['font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $cel_quantidade => ['font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $celula_total => ['font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class=> function(AfterSheet $event) {

                $event->sheet->setAutoFilter('A1:S1');

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(80);

                // Cabeçalho
                $event->sheet->getDelegate()->getStyle('A'.$this->contHeader.':S'.$this->contHeader)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D9D9D9');

                // Linhas Despesas
                if($this->contDespesa > 0) {
                    $event->sheet->getDelegate()->getStyle('A'.($this->contHeader + 1).':S'.($this->contHeader + $this->contDespesa))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF9B9B');
                }

                // Linhas Receitas
                if($this->contReceita > 0) {
                    $event->sheet->getDelegate()->getStyle('A'.($this->contHeader + $this->contDespesa + 1).':S'.($this->contHeader + $this->contDespesa + $this->contReceita))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('C4E59F');
                }

                // Total de Despesas
                $event->sheet->getDelegate()->getStyle('E'.($this->contHeader + $this->contRows + 2).':F'.($this->contHeader + $this->contRows + 2))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF9B9B');


                // Total de Receitas
                $event->sheet->getDelegate()->getStyle('E'.($this->contHeader + $this->contRows + 3).':F'.($this->contHeader + $this->contRows + 3))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('C4E59F');

                // Total Geral
                $event->sheet->getDelegate()->getStyle('F'.($this->contHeader + $this->contRows + 5).':F'.($this->contHeader + $this->contRows + 5))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB(($this->resultado >=0 ? 'C4E59F' : 'FF9B9B'));

                // Total de Machos
                $event->sheet->getDelegate()->getStyle('I'.($this->contHeader + $this->contRows + 3).':I'.($this->contHeader + $this->contRows + 3))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D9D9D9');

                // Total de Fêmeas
                $event->sheet->getDelegate()->getStyle('J'.($this->contHeader + $this->contRows + 3).':J'.($this->contHeader + $this->contRows + 3))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D9D9D9');

                // Quantidade Bovinos
                $event->sheet->getDelegate()->getStyle('I'.($this->contHeader + $this->contRows + 5).':I'.($this->contHeader + $this->contRows + 5))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D9D9D9');


                foreach ($event->sheet->getColumnIterator('M') as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        if (str_contains($cell->getValue() ?? '', '://')) {
                            $cell->setHyperlink(new Hyperlink($cell->getValue(), 'Read'));

                             // Upd: Link styling added
                             $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => 'single'
                                ]
                            ]);
                        }
                    }
                };

            },
        ];

    }

}
