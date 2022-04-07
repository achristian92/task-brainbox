<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(\App\Repositories\Activities\Activity::class, function (Faker $faker) {

    $user_id = $faker->randomElement(\App\User::all()->pluck('id')->toArray());
    $customer_ids = \App\User::find($user_id)->customers()->pluck('customers.id');
    $customer_id = $faker->randomElement($customer_ids);
    $dateCreated = $faker->dateTimeBetween('2020-11-01','2020-12-31');
    $state = $faker->randomElement(['planned','approved','completed']);
    $user_ids = $faker->randomElement(\App\User::all()->pluck('id')->toArray());

    $activities = $faker->randomElement([
        'PRESENTACION Y PAGO AFP MAYO','analisis PARA EEFF','MODIFICACION ACTIVO FIJO','cuadro comparativo ventas 2019-2020',
        'REGISTRO DE VENTAS Y COMPRAS MAYO 2020','PROVISIONES DE PLANILLA ','ACTUALIZACION TABLA EPS JUNIO',
        'ELABORACION LIQ.IMPUESTOS MAYO 2020','Terminar Elaboracion EEFF 2019','ELABORACION DECLARA FACIL  Y PLAME -  MAYO 2020',
        'ANALISIS DE CUENTAS ','REGISTRO DE BANCOS MAYO 2020','ELABORACION EEFF MAYO 2020', 'PRESENTACION IMPUESTOS  MAYO 2020',
        'REGISTRO DE CAJA CHICA MAYO 2020','ENVIO DE CORREO PARA APROBACION DE PLANILLA','CARGAR PAGOS EN TELECREDITO','ELABORACION TIPO DE CAMBIO ',
        'MODIFICACION Y ACTUALizacion EEFF A MARZO', 'revision cuadro depreciacion activo','validacion compras',
        'comparativo RH Sistema y Sunat','REPORTE TRIBUTARIO SUNAT', 'cuadro ventas comparativo 2019-2020','AVANCE BANCOS Y CAJA CHICA',
        'LIQUIDACION  DE IMPUESTOS  MARZO 2020','LIBRO DE ACTIVO  FIJO 2011 Y 2019','FLUJO DE CAJA 2017','REGISTRO EGRESOS MARZO Y ABRIL',
        'INFORME TRANSACCIONES ENE-ABRIL','LIQUIDA IMPUESTOS',' CORRECCION ASIENTOS MAL INGRESADOS','pago afp marzo y mayo',
        'presentacion y declaracion y pago, PLE. PLAME y Declara Facil','CIERRE CONTABLE','CREACION CUENTAS CONTABLES'
    ]);

    return [
        'is_planned'      => 1,
        'customer_id'     => $customer_id,
        'user_id'         => $user_id,
        'name'            => ucfirst(strtolower($activities)),
        'time_estimate'   => $faker->time($format = 'H:i', $max = 'now'),
        'time_real'       => $state === 'completed' ? $faker->time($format = 'H:i', $max = 'now') : NULL,
        'description'     => "Ejemplo de descripción - ".$faker->word(),
        'is_priority'     => $faker->boolean(),
        'status'          => $state,
        'created_by_id'   => $user_id,
        'created_date'    => Carbon::parse($dateCreated)->startOfMonth()->format('Y-m-d'),
        'approved_by_id'  => ($state !== 'planned') ? $user_ids : null,
        'approved_date'   => ($state !== 'planned') ? Carbon::parse($dateCreated)->startOfMonth()->addDays(rand(2,7))->format('Y-m-d') : null,
        'completed_date'  => $state === 'completed' ? $dateCreated : null,
        'start_date'      => $dateCreated,
        'due_date'        => ($state === 'planned') ? Carbon::parse($dateCreated)->addDays(rand(2,7))->format('Y-m-d') : $dateCreated,
        'deadline'        => ($state === 'planned') ? Carbon::parse($dateCreated)->addDays(rand(1,6))->format('Y-m-d') : null
    ];
});
