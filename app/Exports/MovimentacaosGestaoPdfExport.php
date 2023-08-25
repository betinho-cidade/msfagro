<?php

namespace App\Exports;

use App\Models\Movimentacao;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class MovimentacaosGestaoPdfExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithEvents
{

    use Exportable;

    protected $search, $contHeader, $contRows, $contDespesa, $contReceita, $resultado;

    public function __construct(Array $params)
    {
        if(Gate::denies('view_relatorio_gestao')){
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
            'Data',
            'Cliente',
            'Tipo',
            'Valor',                 
            'Item',
            'Empresa',
            'Forma Pagamento',
            'Produtor',
        ];
    }

    public function map($movimentacao): array
    {

        if(!($movimentacao instanceof Movimentacao) && isset($movimentacao['is_summary']) && $movimentacao['is_summary']===true){
            return [
                [],
                ['','','Total Despesa', $movimentacao['total_despesa']],
                ['','','Total Receita', $movimentacao['total_receita']],                
                [],
                ['','','Resultado', $movimentacao['total_geral']],
            ];
          }else{ 
            if ($movimentacao instanceof Movimentacao){
                return [
                    Date::stringToExcel($movimentacao->data_programada_formatada),
                    $movimentacao->cliente->nome_cliente ?? '...',
                    $movimentacao->tipo_movimentacao_texto,
                    $movimentacao->valor ?? 0,
                    $movimentacao->item_texto,
                    $movimentacao->empresa->nome_empresa ?? '...',
                    $movimentacao->forma_pagamento->forma,
                    $movimentacao->produtor->nome_produtor ?? '...',
                ];
            }
          }
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
                                        ->orderBy('movimentacaos.tipo', 'desc') // primeiro por Despesa, depois por Receita
                                        ->orderBy('movimentacaos.data_programada', 'asc');
        
        return $movimentacaos;
    }

    public function prepareRows($movimentacaos){
       
        $total_despesa = 0;
        $total_receita = 0;
        $total_geral = 0;
      
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
        }

        $movimentacaos[]=[
          'is_summary'=>true,
          'total_despesa'=>$total_despesa,
          'total_receita'=>$total_receita,
          'total_geral'=> ($total_receita - $total_despesa)
        ];

        $this->resultado = $total_receita - $total_despesa;

        return $movimentacaos;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }    

    public function styles(Worksheet $sheet)
    {
        $cel_resultado = 'C' . ($this->contHeader + $this->contRows + 5);
        $celula_valor  = 'D' . ($this->contHeader + $this->contRows + 5);

        return [
            // Style the first row as bold text.
            1   => ['font' => ['bold' => true],
                     'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            'A' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],
            'B' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],
            'C' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],
            'D' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],
            'E' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],
            'F' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],                   
            'G' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],                   
            'H' => ['font' => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                   ],                                      
            $cel_resultado => ['font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                    ],                    
            $celula_valor => ['font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true]
                    ],                     
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class=> function(AfterSheet $event) {

                $event->sheet->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                $event->sheet->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);   

                // Cabeçalho
                $event->sheet->getDelegate()->getStyle('A'.$this->contHeader.':H'.$this->contHeader)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D9D9D9');

                // Linhas Despesas
                if($this->contDespesa > 0) {
                    $event->sheet->getDelegate()->getStyle('A'.($this->contHeader + 1).':H'.($this->contHeader + $this->contDespesa))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('C4E59F'); 
                }        

                // Linhas Receitas
                if($this->contReceita > 0) {
                    $event->sheet->getDelegate()->getStyle('A'.($this->contHeader + $this->contDespesa + 1).':H'.($this->contHeader + $this->contDespesa + $this->contReceita))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF9B9B'); 
                }   

                // Total de Despesas
                $event->sheet->getDelegate()->getStyle('C'.($this->contHeader + $this->contRows + 2).':D'.($this->contHeader + $this->contRows + 2))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('C4E59F');                        


                // Total de Receitas
                $event->sheet->getDelegate()->getStyle('C'.($this->contHeader + $this->contRows + 3).':D'.($this->contHeader + $this->contRows + 3))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF9B9B');    
                        
                // Total Geral
                $event->sheet->getDelegate()->getStyle('D'.($this->contHeader + $this->contRows + 5).':D'.($this->contHeader + $this->contRows + 5))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB(($this->resultado >=0 ? 'FF9B9B' : 'C4E59F'));                                                
  
            },
        ];

    }

}
