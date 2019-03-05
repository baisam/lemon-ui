@php
    $end->config('useCurrent', false);
@endphp
<div id="{{ $id }}" class="btn-group btn-group-sm" style="margin-bottom: 0;width: 388px">
    <div class="form-group form-group-sm">
        <div class="col-sm-5">
            {{ $start }}
        </div>
        <div class="col-sm-5">
            {{ $end }}
        </div>
        <div class="col-sm-2">
            <div class="input-group input-group-sm">
                <div class="input-group-btn">
                    {{ $button }}
                </div>
            </div>
        </div>
    </div>
</div>
@script
$('#{{ $startId }}').on("dp.change", function (e) {
$('#{{ $endId }}').data("DateTimePicker").minDate(e.date);
});
$('#{{ $endId }}').on("dp.change", function (e) {
$('#{{ $startId }}').data("DateTimePicker").maxDate(e.date);
});
@endscript