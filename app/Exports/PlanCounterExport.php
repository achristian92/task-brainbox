<?php

namespace App\Exports;

use App\Repositories\Counters\Counter;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;

class PlanCounterExport implements FromView,WithDrawings,ShouldAutoSize,WithCustomStartCell,WithEvents
{

    private $data;
    private $counter;
    private $month;

    public function __construct($data, string $counter, string $month)
    {
        $this->data = $data;
        $this->counter = $counter;
        $this->month = $month;
    }

    public function view(): View
    {
        return view('admin.reports.counter.planned', [
            'data'  => $this->data,
        ]);
    }


    public function drawings()
    {
        return EventExportImageLogo();
    }

    public function startCell(): string
    {
        return 'B9';
    }

    public function registerEvents(): array
    {
        $style    = EventExportStyles();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($style) {
                $event->sheet->setShowGridlines(false);
                $event->sheet->getDelegate()
                    ->setCellValue('b6','Colaborador:')
                    ->setCellValue('b7','Mes:')
                    ->setCellValue('c6',$this->counter)
                    ->setCellValue('c7',$this->month);
                $event->sheet->getDelegate()
                    ->setCellValue('b8','REPORTE DE PLAN DE TRABAJO')
                    ->mergeCells('b8:g8');

                /* Styles */
                $event->sheet->getDelegate()
                    ->getStyle('b6:b7')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()
                    ->getStyle('c6:c7')
                    ->applyFromArray([
                        'font' => [
                            'family'     => 'Calibri',
                            'size'       => '12',
                            'bold'       => true
                        ]
                    ]
                );
                $event->sheet->getDelegate()
                    ->getStyle('b8:h8')
                    ->applyFromArray($style['TITLE']);
                $event->sheet->getDelegate()
                    ->getStyle('b9:h9')
                    ->applyFromArray($style['HEADER']);
                $rowEnd = (9 + $this->data->count() );
                $event->sheet->getDelegate()
                    ->getStyle("b10:h$rowEnd")
                    ->applyFromArray($style['DATA']);

                /* Row Height */
                $event->sheet->getDelegate()->getRowDimension(8)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(9)->setRowHeight(25);
            },
        ];
    }


}
