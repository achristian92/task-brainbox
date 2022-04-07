<?php

namespace App\Imports;


use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Tags\Exceptions\TagNotFoundException;
use App\Repositories\Tags\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;


class PlanCounterImport implements ToCollection,WithHeadingRow
{

    private $user_id, $user;

    public function __construct(int $user_id, User $user)
   {
       $this->user_id = $user_id;
       $this->user = $user;
   }


    public function collection(Collection $rows)
    {
        $rows->each(function ($row,$key) {
            if($row['fecha'] != '' && $row['cliente'] != '' && $row['actividad'] != '' && $row['horas'] != '') {
                $row['fecha'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha']))->format('Y-m-d');
                $row['horas'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['horas']))->format('H:i');
                $this->validationRow($row, $key);

                $activity = Activity::updateOrCreate(
                    [
                        'start_date'    => $row['fecha'],
                        'customer_id'   => $this->searchCustomerByName($row['cliente']),
                        'user_id'       => $this->user_id,
                        'name'          => ucfirst(strtolower($row['actividad'])),
                    ],
                    [
                        'time_estimate' => $row['horas'],
                        'tag_id'        => $this->searchTagByName($row['etiqueta']),
                        'description'   => $row['descripcion'],
                        'due_date'      => $row['fecha'],
                        'is_priority'   => $row['prioridad'] === 'x',
                        'status'        => Activity::TYPE_PLANNED,
                        'created_by_id' => $this->user_id,
                        'created_date'  => Carbon::now()
                    ]
                );


                if ($activity->wasRecentlyCreated === true)
                    $activity->histories()->create([
                        'user'          => $this->user->full_name,
                        'description'   => 'Actividad creada',
                        'registered_at' => Carbon::now()
                    ]);

            }
        });

    }

    public function headingRow(): int
    {
        return 7;
    }

    private function validationRow($row, int $key)
    {
        $currentRow = $key+8;

        $messages = [
            'required'    => "El campo :attribute es requerido en la fila $currentRow.",
            'max'         => "El campo :attribute no debe tener mas de 255 caracteres en la fila $currentRow.",
            'date_format' => "El campo :attribute no corresponde con el formato de fecha Y-m-d/Horas en la fila $currentRow.",
            'exists'      => "El campo :attribute seleccionado en la fila $currentRow  no existe.",
            'numeric'     => "El campo horas debe ser un número en la fila $currentRow.",
            'in'          => "El campo :attribute es inválido en la fila $currentRow."
        ];

        Validator::make($row->toArray(), [
            'fecha'     => 'date_format:Y-m-d|required',
            'cliente'   => 'required|exists:customers,name',
            'actividad' => 'required|max:255',
            'horas'     => 'required|date_format:H:i',
            'etiqueta'  => 'required|exists:tags,name'
        ], $messages)->validate();
    }

    private function searchCustomerByName(string $name)
    {
        try {
            $customer = Customer::where('name',$name)->first();
            return $customer->id;
        } catch (ModelNotFoundException $e) {
            throw new \App\Repositories\Activities\Exceptions\CustomerNotFoundException();
        }
    }

    private function searchTagByName(string $tag = null)
    {
        if (empty($tag))
            return NULL;


        try {
            $tag = Tag::where('name',$tag)->first();
            return $tag->id;
        } catch (ModelNotFoundException $e) {
            throw new TagNotFoundException();
        }
    }

    private function formatHours(string $hours): string
    {
        $format = str_replace(',',':',$hours);
        $findme   = ':';
        $pos = strpos($format, $findme);
        return $pos ? $format : "00:00";

    }
}
