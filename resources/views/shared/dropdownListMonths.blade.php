<div class="dropdown show dropleft">
    <a class="btn btn-sm btn-outline-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLinkMonths" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{_months()[request('month',_currentMonth())]}}
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLinkMonths">
        @foreach(_months() as $key => $month)
            <a class="dropdown-item" href="{{route($router_link,"month=$key")}}">{{$month}}</a>
        @endforeach
    </div>
</div>
