<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TestExport implements FromCollection,WithEvents
{

    public function collection()
    {
        return User::all()->transform(function ($items) {
            return [
                'name' => $items['name'],
                'created_at' => Carbon::now()->format('H:i'),
                'numero' => 10.30,
                ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event)  {
                $event->sheet->setShowGridlines(false);


              $event->sheet->getDelegate()
                    ->getStyle('B1:B5')
                   ->getNumberFormat()
                  ->setFormatCode("yyyy");

                    //->getNumberFormat()->setFormatCode('[HH]:MM');
            },
        ];
    }


}
