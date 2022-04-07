<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;

class CustomerHistoryHours implements FromView,WithDrawings,ShouldAutoSize,WithCustomStartCell,WithEvents
{
    private $data;
    private $rangeMonths;

    public function __construct(array $data,array $rangeMonths)
    {
                $this->data = $data;
         $this->rangeMonths = $rangeMonths;
    }

    public function view(): View
    {
        return view('admin.reports.customer.history-hours-last-month', [
            'customers' => $this->data,
         'months_range' => $this->rangeMonths
        ]);
    }

    public function startCell(): string
    {
        return 'B8';
    }

    public function drawings()
    {
        return EventExportImageLogo();
    }

    public function registerEvents(): array
    {
        $style    = EventExportStyles();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($style) {
                $event->sheet->setShowGridlines(false);

                $event->sheet->getDelegate()
                    ->getStyle('b8')
                    ->applyFromArray($style['TITLE']);

                /** Days */
                $current_column = 'C';

                for($i=1; $i < count($this->rangeMonths); $i++) {
                    $current_column++; // Increment letter
                }

                $event->sheet->getDelegate()
                    ->getStyle("c8:".$current_column."8")
                    ->applyFromArray([
                        'font' => [
                            'family'     => 'Calibri',
                            'size'       => '12',
                            'bold'       => true
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText' => true
                        ],
                    ]);

                /** Data */
                $end_row = count($this->data) + 8;

                $event->sheet->getDelegate()
                    ->getStyle("b8:$current_column$end_row")
                    ->applyFromArray($style['DATA']);

                $event->sheet->getDelegate()->getRowDimension(8)->setRowHeight(25);
            },
        ];
    }
}
