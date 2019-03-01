<div class="input-group" style="width: 160px;">
    <span class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </span>
    <input id="{{ $id }}" type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>
</div>
@php
    $config['locale'] = config('app.locale');
    if (isset($format)) {
        //Y-m-d H:i:s => YYYY-MM-DD hh:mm:ss
        $format = str_replace(
            ['Y',    'y',  'm',  'n', 'd',  'j', 'H',  'G', 'h',  'g', 'i',  's'],
            ['YYYY', 'YY', 'MM', 'M', 'DD', 'D', 'HH', 'H', 'hh', 'h', 'mm', 'ss'],
             $format);
        $config['format'] = $format;
    }
@endphp

@script
$(function() {
  $('#{{ $id }}').datetimepicker(@json($config));
  @if($minDate)
  $('#{{ $id }}').data("DateTimePicker").minDate('{{ $minDate }}');
  @endif
  @if($maxDate)
  $('#{{ $id }}').data("DateTimePicker").maxDate('{{ $maxDate }}');
  @endif
});
@endscript