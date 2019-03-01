@foreach($options as $option => $label)
<div class="radio {{ $class }}">
    <input id="{{ $id.$loop->iteration }}" type="radio" name="{{ $name }}" value="{{ $option }}" {{ $option == $value ? 'checked' : '' }} {!! $attributes !!}>
    <label for="{{ $id.$loop->iteration }}">{{ $label }}</label>
</div>
@endforeach

@script($_resource_name, 'radio')
$(function() {
  $('.radio').iCheck({radioClass:'iradio_minimal-blue'});
});
@endscript