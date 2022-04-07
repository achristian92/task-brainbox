<?php

function EventExportImageLogo($coordinates = "B2")
{
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath(public_path('/img/brainbox_logo(2).png'));
    $drawing->setHeight(40);
    $drawing->setWidth(100);
    $drawing->setCoordinates($coordinates);

    return $drawing;
}

function EventExportStyles()
{
    return [
        'TITLE'=> [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => 12,
                'color' => ['argb' => 'ffffff'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '292B57',
                ],
            ],
        ],
        'HEADER' => [
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => 11,
                'color' => ['argb' => '434448'],
            ],
        ],
        'DATA' => [
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        ]
    ];
}
