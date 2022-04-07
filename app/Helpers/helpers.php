<?php


use App\Repositories\Activities\Activity;
use Illuminate\Support\Facades\Auth;

function l($string = NULL, $data = [], $langfile = NULL)
{
    if ( is_string($data) && ($langfile == NULL) ) {
        $langfile = $data;
        $data = [];
    }

    if (Lang::has($langfile.'.'.$string))
        return Lang::get($langfile.'.'.$string, $data);
    else
    {
        foreach ($data as $key => $value)
        {
            $string = str_replace(':'.$key, $value, $string);
        }
        return $string;
    }
}

function isActiveRoute($segmento, $route)
{
    if (request()->segment($segmento) == $route) { return "active"; }

    return '';
}
function expanded($segmento, $route):string
{
    if (request()->segment($segmento) == $route) { return 'true'; }

    return 'false';
}
function show($segmento, $route):string
{
    if (request()->segment($segmento) == $route) { return 'show'; }

    return '';
}

function isActiveRouteCollapse($segmento,$route)
{
    if (request()->segment($segmento) == $route) {
        return "show";
    }

    return '';
}

if (!function_exists('_add4NumRand')) {
    function _add4NumRand(String $string = "123456")
    {
        $text_without_spaces = str_replace(' ', '', $string);
        return strtolower($text_without_spaces.rand(1000,9999));
    }
}

if (!function_exists('_bgProgress')) {
    function _bgProgress(int $progress = 0)
    {
        if ($progress === 0) return '';

        if ($progress >= 90) {
            return 'bg-green';
        }  else if ($progress > 50 && $progress < 90) {
            return 'bg-info';
        } else {
            return 'bg-danger';
        }
    }
}

if (!function_exists('_colorBtnOutline')) {
    function _colorBtnOutline(string $status = 'planned')
    {
        if ($status === Activity::TYPE_PLANNED)  return 'btn-outline-plomo';
        if ($status === Activity::TYPE_APPROVED) return  'btn-outline-warning';
        if ($status === Activity::TYPE_EVALUATION) return  'btn-outline-warning';
        if ($status === Activity::TYPE_COMPLETED) return  'btn-outline-success';
        if ($status === Activity::TYPE_OVERDUE) return 'btn-outline-danger';
        if ($status === Activity::TYPE_PARTIAL) return 'btn-outline-success';

        return 'btn-outline-plomo';
    }
}

if (!function_exists('_colorStatusHex')) {
    function _colorStatusHex(string $status = 'planned')
    {
        if ($status === Activity::TYPE_PLANNED)  return '#ced4da';
        if ($status === Activity::TYPE_APPROVED) return  '#ffd600';
        if ($status === Activity::TYPE_EVALUATION) return  '#ffd600';
        if ($status === Activity::TYPE_COMPLETED) return  '#2dce89';
        if ($status === Activity::TYPE_OVERDUE) return '#f5365c';
        if ($status === Activity::TYPE_PARTIAL) return '#2bffc6';

        return '#ced4da';
    }
}

if (!function_exists('_colorStatusBg')) {
    function _colorStatusBg(string $status = 'planned')
    {
        if ($status === Activity::TYPE_PLANNED)  return 'bg-plomolight';
        if ($status === Activity::TYPE_APPROVED) return  'bg-warning';
        if ($status === Activity::TYPE_EVALUATION) return  'bg-warning';
        if ($status === Activity::TYPE_COMPLETED) return  'bg-green';
//        if ($status === Activity::TYPE_OVERDUE) return 'bg-danger';
        if ($status === Activity::TYPE_PARTIAL) return 'bg-yellow';

        return 'bg-plomolight';
    }
}

if (!function_exists('_months')) {
    function _months()
    {
        return [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Setiembre',
            10 => 'Octubre',
            11 =>'Noviembre',
            12 => 'Diciembre',
        ];
    }
}
if (!function_exists('_currentMonth')) {
    function _currentMonth():int
    {
        return date('m');
    }
}

function _sumTime(array $times) {
    $minutes = 0; //declare minutes either it gives Notice: Undefined variable
    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    return sprintf('%02d:%02d', $hours, $minutes);
}
function _formatTimeWorked(string $timeWorked): string
{
    $cut = explode(':',$timeWorked);
    $hour = $cut[0].' h';
    $min = $cut[1].' m';

    return "$hour $min";
}

function _addDocumentHistory(string $type, string $description,$fullPath = null) {
    Auth::user()->documents()->create([
        'user_full_name' => \Auth::user()->full_name,
        'type'           => $type,
        'name'           => $description,
        'url_file'       => $fullPath
    ]);
}

function _addHistory(string $type, string $description,$model = null) {
    Auth::user()->histories()->create([
        'user_full_name' => Auth::user()->full_name,
        'type'           => $type,
        'description'    => $description,
        'model'          => $model
    ]);
}
