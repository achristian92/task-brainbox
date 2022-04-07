<?php


namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class CounterTotalHoursCustomers implements FromCollection,WithHeadings,WithDrawings,ShouldAutoSize,WithCustomStartCell,WithEvents
{
    private $data;
    private $counter;
    private $date;

    public function __construct($data, string $counter_name, string $startAndDueDate)
    {
        $this->data = $data;
        $this->counter = $counter_name;
        $this->date = $startAndDueDate;
    }

    public function collection()
    {
        return $this->data;
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
        $style = EventExportStyles();
        return [
            AfterSheet::class => function(AfterSheet $event) use ($style) {
                $event->sheet->setShowGridlines(false);
                $event->sheet->getDelegate()
                    ->setCellValue('b6','Colaborador:')
                    ->setCellValue('b7','Fecha:')
                    ->setCellValue('c6',$this->counter)
                    ->setCellValue('c7',$this->date);
                $event->sheet->getDelegate()
                    ->setCellValue('b8','REPORTE TOTAL HORAS POR CLIENTE')
                    ->mergeCells('b8:e8');

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
                    ->getStyle('b8:e8')
                    ->applyFromArray($style['TITLE']);
                $event->sheet->getDelegate()
                    ->getStyle('b9:e9')
                    ->applyFromArray($style['HEADER']);
                $rowEnd = (9 + $this->data->count() );
                $event->sheet->getDelegate()
                    ->getStyle("b10:e$rowEnd")
                    ->applyFromArray($style['DATA']);
                /**
                 *Total
                 */
                $end = $rowEnd+1;
                $event->sheet->getDelegate()
                    ->setCellValue("c$end",'TOTAL:')
                    ->getStyle("c$end")
                    ->applyFromArray([
                            'font' => [
                                'family'     => 'Calibri',
                                'size'       => '12',
                                'bold'       => true
                            ]
                        ]
                    )->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                $event->sheet->getDelegate()
                    ->setCellValue("d$end",_sumTime($this->data->pluck('totalEstimatedTime')->toArray()));
                $event->sheet->getDelegate()
                    ->setCellValue("e$end",_sumTime($this->data->pluck('totalRealTime')->toArray()));
                /* Row Height */
                $event->sheet->getDelegate()->getRowDimension(8)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(9)->setRowHeight(25);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'FECHA',
            ' CLIENTES ',
            ' ESTIMADO ',
            ' REAL ',
        ];
    }
}
