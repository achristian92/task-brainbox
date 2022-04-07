@include('components.errors-and-messages')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6"><h3 class="mb-0">{{ $title }}</h3></div>
                    <div class="col-md-6 text-right" >
                        {{ $actions }}
                    </div>
                </div>
                <br>
                <div class="table-responsive table-size-12">
                    {{ $table }}
                </div>
            </div>
        </div>
    </div>
</div>
