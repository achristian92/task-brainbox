<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateImportPlan implements FromCollection,WithEvents,WithDrawings
{
    private $customers;
    private $tags;

    public function __construct($customers, $tags)
    {
        $this->customers = $customers;
        $this->tags = $tags;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
            /** NOTES */
                $event->sheet->getDelegate()
                    ->setCellValue('C1','CAMPOS OBLIGATORIOS:')
                    ->setCellValue('C2','* Fecha, Cliente, Actividad y Horas son obligatorias')
                    ->setCellValue('C3','NOTAS:')
                    ->setCellValue('C4','* El cliente y etiqueta tienes que seleccionar de la lista. Si la actividad tiene priodad alta ingresar "x"')
                    ->setCellValue('C5','* La fecha debe ser d/m/Y y hora debe ser H:m (ej: 05:30)');
            /** Headers */
                $event->sheet->getDelegate()
                    ->setCellValue('A6','PLAN DE TRABAJO')
                    ->mergeCells('A6:G6');
                $event->sheet->getDelegate()
                    ->setCellValue('A7','FECHA')
                    ->setCellValue('B7','CLIENTE')
                    ->setCellValue('C7','ACTIVIDAD')
                    ->setCellValue('D7','HORAS')
                    ->setCellValue('E7','PRIORIDAD')
                    ->setCellValue('F7','ETIQUETA')
                    ->setCellValue('G7','DESCRIPCIÓN');

            /** Add Customer */
                $totalCustomer = $this->customers->count();
                foreach ($this->customers as $key => $customer) {
                    $row = $key+1;
                    $event->sheet->getDelegate()
                        ->setCellValue("K$row",$customer);
                }
                $event->sheet->getDelegate()->getParent()
                    ->addNamedRange( new NamedRange('ListCustomers', $event->sheet->getDelegate(), "K1:K$totalCustomer") );

                $event->sheet->getDelegate()->getColumnDimension('K')->setVisible(false);

                /** Add Tags */
                $totalTags = $this->tags->count();
                foreach ($this->tags as $key => $tag) {
                    $row = $key+1;
                    $event->sheet->getDelegate()
                        ->setCellValue("L$row",$tag);
                }
                $event->sheet->getDelegate()->getParent()
                    ->addNamedRange( new NamedRange('ListTags', $event->sheet->getDelegate(), "L1:L$totalTags") );

                $event->sheet->getDelegate()->getColumnDimension('L')->setVisible(false);



                $sheet = $event->sheet->getDelegate();

                for ($a = 0; $a<100; $a++)
                {
                    $coordinate = $a+8;
                    // Customers
                    $validation = $sheet->getCell("B$coordinate")->getDataValidation();
                    $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
                    $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error');
                    $validation->setError('Selecciona un cliente de la lista.');
                    $validation->setPromptTitle('Selecciona un cliente');
                    $validation->setFormula1('ListCustomers');
                    // TAGS
                    $validation = $sheet->getCell("F$coordinate")->getDataValidation();
                    $validation->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
                    $validation->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Error');
                    $validation->setError('Selecciona una etiqueta de la lista.');
                    $validation->setPromptTitle('Selecciona una etiqueta');
                    $validation->setFormula1('ListTags');
                }



                /* Row Height */
                $event->sheet->getDelegate()->getRowDimension(6)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(7)->setRowHeight(25);
                $largeColumns = ['B','C','G'];
                $smallColumns = ['E','F'];
                foreach ($largeColumns as $colum) {
                    $event->sheet->getDelegate()->getColumnDimension($colum)->setWidth(30);
                }

                foreach ($smallColumns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(15);
                }
                /**
                 *ADD STYLES
                 */
                $style = EventExportStyles();
                $event->sheet->getDelegate()
                    ->getStyle('C1')
                    ->applyFromArray([
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  12,
                            'bold'      =>  true
                        )
                    ]);
                $event->sheet->getDelegate()
                    ->getStyle('C3')
                    ->applyFromArray([
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  12,
                            'bold'      =>  true
                        )
                    ]);
                $event->sheet->getDelegate()
                    ->getStyle('A1:G5')
                    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
                //TITLE
                $event->sheet->getDelegate()
                    ->getStyle('A6:G6')
                    ->applyFromArray($style['TITLE']);
                // HEADER
                $event->sheet->getDelegate()
                    ->getStyle('A7:G7')
                    ->applyFromArray($style['HEADER']);
            }
        ];
    }

    public function drawings()
    {
        return EventExportImageLogo("B2");
    }
}
