<textarea id="{{ $id }}" name="{{ $name }}" class="form-control {{ $class }}" style="height: auto;" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ $value }}</textarea>
@php
    $config = array_merge($config, [
        'allowOverMax' => true,
        'alwaysShow' => true,
        'validate' => true,
        'appendToParent' => true,
        'placement' => 'bottom-right-inside'
    ]);
@endphp
@script
$(function() {
  $('#{{ $id }}').maxlength(@json($config));
});
@endscript