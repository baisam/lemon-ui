@if($single && count($options) === 1)
<div class="checkbox {{ $class }}">
    <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="{{ array_keys($options)[0] }}" {{ array_keys($options)[0] == $value ? 'checked' : '' }} {!! $attributes !!}>
    <label for="{{ $id }}">{{ array_values($options)[0] }}</label>
</div>
@else
    @foreach($options as $option => $label)
        <div class="checkbox {{ $class }}">
            <input id="{{ $id.$loop->iteration }}" type="checkbox" name="{{ $name }}[]" value="{{ $option }}" {{ in_array($option, (array)$value) ? 'checked' : '' }} {!! $attributes !!}>
            <label for="{{ $id.$loop->iteration }}">{{ $label }}</label>
        </div>
    @endforeach
@endif

@script($_resource_name, 'checkbox')
$(function() {
  $('.checkbox').iCheck({checkboxClass:'icheckbox_minimal-blue'});
});
@endscript