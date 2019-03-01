<input id="{{ $id }}" type="{{ $input }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>

@php
    $config = array_merge($config, [
        'type' => 'single',
        'prettify' => false,
        'hasGrid' => true,
        'min' => $min,
        'max' => $max
    ]);
@endphp
@script
$(function() {
  $('#{{ $id }}').ionRangeSlider(@json($config));
});
@endscript