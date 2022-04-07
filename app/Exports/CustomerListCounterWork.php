<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerListCounterWork implements FromView,WithDrawings,ShouldAutoSize,WithCustomStartCell,WithEvents
{
    private $data;
    private $month;
    private $dates;
    private $customer;

    public function __construct(array $data,array $period, string $month, string $customer)
    {
         $this->data = $data;
        $this->dates = $period;
        $this->month = $month;
     $this->customer = $customer;
    }

    public function view(): View
    {
        return view('admin.reports.customer.list-counter-worked', [
            'counters' => $this->data,
               'dates' => $this->dates,
               'total' => self::sumTotalHoursByDay()
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

        return [
            AfterSheet::class => function(AfterSheet $event) use ($style) {
                $event->sheet->setShowGridlines(false);
                $event->sheet->getDelegate()
                    ->setCellValue('b6',"Cliente: $this->customer")
                    ->setCellValue('b7',"Fecha: $this->month");

                /* Styles */
                $event->sheet->getDelegate()
                    ->getStyle('b8')
                    ->applyFromArray($style['TITLE']);

                $event->sheet->getDelegate()
                    ->getStyle("b6:b7")
                    ->applyFromArray([
                        'font' => [
                            'family'     => 'Calibri',
                            'size'       => '15',
                            'bold'       => true
                        ],
                    ]);

                /** Days */
                $current_column = 'C';

                for($i=1; $i < count($this->dates); $i++) {
                    $current_column++; // Increment letter
                }

                $current_column++;
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
    private function sumTotalHoursByDay(): array
    {
        foreach ($this->dates as $key => $date) {
            $values=[];
            foreach ($this->data as $value) {
                array_push($values,$value['dates'][$key]);
            }
            $totales[] = _sumTime($values);
        }
        return $totales;
    }

}
