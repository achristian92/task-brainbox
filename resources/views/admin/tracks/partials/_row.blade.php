    <tr style="height: 48px">
        <td><h5 class="mb-0"><a href="{{ route('admin.tracks.show',$track['id']) }}" >{{ $track['name'] }}</a></h5></td>
        <td class="text-right text-danger">
            @if($track['qtyOverdue'] > 0)
                <strong>{{ $track['qtyOverdue'] }}</strong>
            @endif
        </td>
        <td class="text-right">
            <span class="text-success"><i class="ni ni-check-bold mr-1"></i><strong>{{ $track['qtyCompleted'] }}</strong></span> /
            <span class="text-primary"><strong>{{ $track['total'] }}</strong></span>
        </td>
        <td class="text-right">
            <i class="far fa-clock mr-1"></i>
            <strong>{{ $track['estimatedTime'] }}</strong> / <strong>{{ $track['realTime'] }}</strong>
        </td>
        <td class="text-right">
            <div class="d-flex align-items-center">
                <span class="completion mr-2">{{ $track['progress'] }}%</span>
                <div>
                    <div class="progress">
                        <div class="progress-bar {{ $track['bgProgress'] }}" role="progressbar" aria-valuemin="0" aria-valuemax="100" :style="{width: {{ $track['progress'] }}+'%'}"></div>
                    </div>
                </div>
            </div>
        </td>
        <td class="text-right">
            @if($track['hoursWorked'] > 180)
                <i class="fas fa-arrow-up text-primary mr-1"></i>
            @elseif($track['hoursWorked'] === 180)
                <i class="fas fa-check text-success mr-1"></i>
            @elseif($track['hoursWorked'] === 0)
                <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
            @endif
            <strong>{{ $track['performance'] }}%</strong>
        </td>
        <td class="text-right">
            <a href="{{ route('admin.tracks.show',$track['id']) }}">
                <span class="text-primary"><i class="far fa-eye mr-2"></i></span>
            </a>
        </td>

    </tr>


