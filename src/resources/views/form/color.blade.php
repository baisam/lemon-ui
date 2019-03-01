<div class="input-group" style="width: 140px;">
    <span class="input-group-addon">
        <i></i>
    </span>
    <input id="{{ $id }}" type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control colorpicker-element {{ $class }}" {!! $attributes !!}>
</div>
@php
    if (isset($format)) {
        $config['format'] = $format;
    }
    if (!isset($config['align'])) {
        $config['align'] = 'left';
    }
@endphp

@script
$(function() {
  $('#{{ $id }}').parent().colorpicker(@json($config));
});
@endscript