<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ActivitiesExport implements FromView,WithDrawings,ShouldAutoSize,WithCustomStartCell,WithEvents
{

    private $data;
    private $rangeDate;

    public function __construct($data, string $rangeDate)
    {
            $this->data = $data;
       $this->rangeDate = $rangeDate;
    }
    public function view(): View
    {
        return view('admin.reports.activity.list-activities', [
            'activities' => $this->data,
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
        $style    = EventExportStyles();
        $endRow   = count($this->data) + 9;
        return [
            AfterSheet::class => function(AfterSheet $event) use ($style,$endRow) {
                $event->sheet->setShowGridlines(false);
                $event->sheet->getDelegate()
                    ->setCellValue('b7','Fecha:')
                    ->setCellValue('c7',$this->rangeDate);
                $event->sheet->getDelegate()
                    ->setCellValue('b8','REPORTE DE ACTIVIDADES')
                    ->mergeCells('b8:i8');
                /** STYLES */
                $event->sheet->getDelegate()
                    ->getStyle('b7')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()
                    ->getStyle('c7')
                    ->applyFromArray([
                            'font' => [
                                'family'     => 'Calibri',
                                'size'       => '12',
                                'bold'       => true
                            ]
                        ]
                    );
                $event->sheet->getDelegate()
                    ->getStyle('b8:i8')
                    ->applyFromArray($style['TITLE']);

                $event->sheet->getDelegate()
                    ->getStyle('b9:i9')
                    ->applyFromArray($style['HEADER']);

                $event->sheet->getDelegate()
                    ->getStyle("b10:i$endRow")
                    ->applyFromArray($style['DATA']);

                $event->sheet->getDelegate()->getRowDimension(8)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(9)->setRowHeight(25);
            },
        ];
    }

}
