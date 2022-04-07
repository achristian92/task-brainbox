<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CounterPlannedVsRealExport implements FromView,WithDrawings,ShouldAutoSize,WithCustomStartCell,WithEvents
{

    private $data;
    private $counter;
    private $rangeDate;

    public function __construct($data, string $username, string $rangeDate)
    {
           $this->data = $data;
        $this->counter = $username;
      $this->rangeDate = $rangeDate;
    }

    public function view(): View
    {
        return view('admin.reports.counter.planned-vs-real', [
            'customers'          => $this->data,
            'totalEstimatedTime' => $this->totalEstimatedTime(),
            'totalRealTime'      => $this->totalRealTime(),
        ]);
    }


    public function startCell(): string
    {
        return 'B9';
    }

    public function drawings()
    {
        return EventExportImageLogo();
    }

    public function registerEvents(): array
    {
        $rowStart =  10;
        $style    = EventExportStyles();
        $totalDataRow = self::countTotalRow();
        $totalRow = $rowStart + $totalDataRow;
        return [
            AfterSheet::class => function(AfterSheet $event) use ($style,$totalRow) {
                $event->sheet->setShowGridlines(false);
                $event->sheet->getDelegate()
                    ->setCellValue('b6','Colaborador:')
                    ->setCellValue('b7','Fecha:')
                    ->setCellValue('c6',$this->counter)
                    ->setCellValue('c7',$this->rangeDate);
                $event->sheet->getDelegate()
                    ->setCellValue('b8','REPORTE DE LO PLANIFICADO VS REAL')
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
                    ->getStyle('b8:g8')
                    ->applyFromArray($style['TITLE']);
                $event->sheet->getDelegate()
                    ->getStyle('b9:g9')
                    ->applyFromArray($style['HEADER']);

                $event->sheet->getDelegate()->getRowDimension(8)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(9)->setRowHeight(25);
                /*
                color status text
                */
                $event->sheet->getDelegate()
                    ->getStyle("f10:f$totalRow")
                    ->applyFromArray([
                        'font' => [
                            'name' => 'Calibri',
                            'size' => 12,
                            'color' => ['argb' => 'ffffff'],
                        ],
                    ]);
                /*
                 * Total
                 * */
                $event->sheet->getDelegate()
                    ->getStyle("a$totalRow:c$totalRow")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }


    private function totalEstimatedTime(): string
    {
        return _sumTime($this->data->pluck('totalEstimatedTime')->toArray());
    }

    private function totalRealTime(): string
    {
        return _sumTime($this->data->pluck('totalRealTime')->toArray());
    }

    private function countTotalRow(): int
    {
        $countTotalCustomer = $this->data->count();
        $countTotalActivitiesByCustomers = $this->data->reduce(function ($total,$customer) {
            return $total + $customer['activities']->count();
        },0);

        return $countTotalCustomer + $countTotalActivitiesByCustomers;
    }
}
