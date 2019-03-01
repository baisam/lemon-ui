@php
    $end->config('useCurrent', false);
@endphp
<div class="row" style="width: 358px">
    <div class="col-lg-6">
        {{ $start }}
    </div>

    <div class="col-lg-6">
        {{ $end }}
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