<?php


namespace App\Repositories\Tools;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

trait DatesTrait
{
    public function getDateFormats(string $yy_mm = null): array
    {
        $dateFormat = Carbon::createFromDate(); // current date
        if ($yy_mm)
            $dateFormat = Carbon::createFromDate($yy_mm);

        $startOfMonth = Carbon::parse($dateFormat)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::parse($dateFormat)->endOfMonth()->format('Y-m-d');

        return [
          'from' => $startOfMonth,
          'to'   => $endOfMonth
        ];

    }

    public function beginEndMonth(Request $request):array
    {
        $month = null;
        if ($request->filled('filter_month')) {
            $month = $request->input('filter_month');
        }

        $dateFormat = Carbon::createFromDate(null, $month,1);

        if ($request->filled('yearAndMonth')) {
            $dateFormat = Carbon::createFromDate($request->input('yearAndMonth'));
        }

        $startRequest = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $dueRequest   = $request->input('due_date', Carbon::now()->format('Y-m-d'));


        $startOfMonth = Carbon::parse($dateFormat)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::parse($dateFormat)->endOfMonth()->format('Y-m-d');

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        $rangeDays = [];
        foreach($period as $date) {
            $rangeDays[] = $date->format('Y-m-d');
        }

        $subDateMonth = Carbon::parse($startOfMonth)->subMonths(6)->format('Y-m-d');
        $periodMonth = CarbonPeriod::create($subDateMonth, '1 month', $endOfMonth);
        $rangeMonth = [];
        $rangeMonthName = [];
        foreach ($periodMonth as $month) {
            $rangeMonth[] = $month->format("Y-m");
            $rangeMonthName[] = $month->monthName;
        }

        return [
            'datetime'       => Carbon::now()->format('Ymdhis'),
            'format'         => $dateFormat,
            'begin'          => $startOfMonth,
            'end'            => $endOfMonth,
            'range'          => $rangeDays,
            'beginR'         => $startRequest,
            'endR'           => $dueRequest,
            'unionR'         => Carbon::parse($startRequest)->format('d/m/y').' hasta '. Carbon::parse($dueRequest)->format('d/m/y'),
            'subMonth'       => $subDateMonth,
            'rangeMonth'     => $rangeMonth,
            'rangeMonthName' => $rangeMonthName,
        ];

    }

    public function getLastMonths(array $starting, int $sub = 6) //Empezar desde, nro meses atras
    {
        $subDateMonth = Carbon::parse($starting['from'])->subMonths($sub)->format('Y-m-d');
        $periodMonth = CarbonPeriod::create($subDateMonth, '1 month', $starting['to']);
        $rangeMonth = [];
        $rangeMonthName = [];
        foreach ($periodMonth as $month) {
            $rangeMonth[] = $month->format("Y-m");
            $rangeMonthName[] = ucfirst($month->monthName);
        }
        return [
            'from'     => $subDateMonth,
            'formatYm' => $rangeMonth,
            'names'    => $rangeMonthName
        ];
    }


    public function addTimeAtDate(array $period,$activities, string $time = 'realTime')
    {
        foreach ($period as $date) {
            $values[] = _sumTime(collect($activities)
                ->where('startDate',$date)
                ->pluck($time)
                ->toArray());
        }

        return $values;
    }

}
