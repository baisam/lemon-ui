<div class="input-group clockpicker">
    <input id="{{ $id }}" type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
@php
    $config['default'] = 'now';
    if (!isset($config['autoclose'])) {
        $config['autoclose'] = true;
    }
@endphp

@script
$(function() {
  $('#{{ $id }}').parent().clockpicker(@json($config));
});
@endscript