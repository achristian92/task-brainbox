<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerExport implements FromView,ShouldAutoSize,WithCustomStartCell,WithEvents
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.customer',[
            'customers' => $this->data
        ]);
    }

    public function startCell(): string
    {
        return 'B2';
    }

    public function registerEvents(): array
    {
        $style    = EventExportStyles();
        return [
            AfterSheet::class => function(AfterSheet $event) use ($style) {
                $event->sheet->setShowGridlines(false);
                /* Styles */
                $event->sheet->getDelegate()
                    ->getStyle('A1:C1')
                    ->applyFromArray($style['HEADER']);

                /* Row Height */
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(25);
            }
        ];
    }

}
