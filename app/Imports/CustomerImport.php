<?php

namespace App\Imports;

use App\Repositories\Customers\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements  ToCollection,WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $rows->each(function ($row,$key) {
            $this->validationRow($row, $key);

            Customer::updateOrCreate(
                [
                    'name' => $row['empresa'],
                ],
                [
                    'address'            => $row['direccion'],
                    'ruc'                => $row['ruc'],
                    'hours'              => empty($row['horas_trabajo_mensual']) ? null : $row['horas_trabajo_mensual'],
                    'contact_email'      => $row['correo_contacto'],
                    'contact_name'       => $row['nombre_contacto'],
                    'contact_telephone'  => $row['telefono_contacto'],
                    'notify_excess_time' => (bool) $row['notificar_si_excedo_horas'] === 'si' ? 1 : 0
                ]
            );

        });

    }

    public function headingRow(): int
    {
        return 1;
    }

    private function validationRow($row, int $key)
    {
        $currentRow = $key+1;

        $messages = [
            'required' => "El campo :attribute es requerido en la fila $currentRow.",
            'max' => "El campo :attribute no debe tener mas de 255 caracteres en la fila $currentRow.",
        ];

        Validator::make($row->toArray(), [
            'empresa' => 'required|max:255',
        ], $messages)->validate();
    }

}
