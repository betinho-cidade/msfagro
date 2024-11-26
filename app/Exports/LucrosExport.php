<?php

namespace App\Exports;

use App\Models\Lucro;
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


class LucrosExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, WithEvents
{

    use Exportable;

    protected $search, $contHeader, $contRows, $contLancamento, $resultado;

    public function __construct(Array $params)
    {
        if(Gate::denies('view_relatorio')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $this->search = $params;
        $this->contHeader = 1;
        $this->contRows = 0;
        $this->contLancamento = 0;
        $this->resultado = 0;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Data Lançamento',
            'Produtor',
            'Forma Pagamento',
            'Valor',
            'Link Comprovante',
            'Observação',
        ];
    }

    public function map($lucro): array
    {
        if(!($lucro instanceof Lucro) && isset($lucro['is_summary']) && $lucro['is_summary']===true){
            return [
                [],
                ['','','','Total Lançamento', $lucro['total_lancamento']],
            ];
          }else{
            if ($lucro instanceof Lucro){
                return [
                    $lucro->id,
                    Date::stringToExcel($lucro->data_lancamento_formatada),
                    $lucro->produtor->nome_produtor ?? '...',
                    $lucro->forma_pagamento->forma,
                    $lucro->valor ?? 0,
                    $lucro->link_comprovante_guest,
                    $lucro->observacao ?? ' '
                ];
            }
          }
    }

    public function query()
    {

        $search = $this->search;

        $user = Auth()->User();

        $lucros = Lucro::where('cliente_id', $user->cliente_user->cliente->id)
                        ->where(function($query) use ($search){

                            if($search && $search['produtor']){
                                $query->where('produtor_id', $search['produtor']);
                            }

                            if($search && $search['forma_pagamento']){
                                $query->where('forma_pagamento_id', $search['forma_pagamento']);
                            }

                            if($search && $search['observacao']){
                                $query->where('observacao', 'like', '%' . $search['observacao'] . '%');
                            }

                            if($search && $search['data_inicio'] && $search['data_fim']){
                                $query->where('data_lancamento', '>=', $search['data_inicio']);
                                $query->where('data_lancamento', '<=', $search['data_fim']);
                            } elseif($search && $search['data_inicio']){
                                $query->where('data_lancamento', '>=', $search['data_inicio']);
                            } elseif($search && $search['data_fim']){
                                $query->where('data_lancamento', '<=', $search['data_fim']);
                            }
                        })
                        ->orderBy('lucros.data_lancamento', 'desc');

        return $lucros;
    }

    public function prepareRows($lucros){

        $total_lancamento = 0;

        foreach($lucros as $row){

            $this->contRows += 1;

            $total_lancamento+=$row->valor;
            $this->contLancamento += 1;
        }

        $lucros[]=[
          'is_summary'=>true,
          'total_lancamento'=>$total_lancamento,
        ];

        $this->resultado = $total_lancamento;

        return $lucros;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $cel_resultado = 'D' . ($this->contHeader + $this->contRows + 5);
        $celula_valor  = 'E' . ($this->contHeader + $this->contRows + 5);

        $celula_texto  = 'H' . ($this->contHeader + $this->contRows + 3);

        $celula_total  = 'I' . ($this->contHeader + $this->contRows + 5);

        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true],
                     'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            $cel_resultado => ['font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $celula_valor => ['font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                    ],
            $celula_texto => ['font' => ['bold' => true, 'size' => 12],
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

                $event->sheet->setAutoFilter('A1:G1');

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(50);

                // Cabeçalho
                $event->sheet->getDelegate()->getStyle('A'.$this->contHeader.':G'.$this->contHeader)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('D9D9D9');

                // Linhas Lancamento
                if($this->contLancamento > 0) {
                    $event->sheet->getDelegate()->getStyle('A'.($this->contHeader + 1).':G'.($this->contHeader + $this->contLancamento))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FF9B9B');
                }

                // Total Geral
                $event->sheet->getDelegate()->getStyle('D'.($this->contHeader + $this->contRows + 2).':E'.($this->contHeader + $this->contRows + 2))
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB(($this->resultado >=0 ? 'C4E59F' : 'FF9B9B'));


                foreach ($event->sheet->getColumnIterator('F') as $row) {
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
