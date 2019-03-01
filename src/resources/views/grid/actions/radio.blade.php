<div class="btn-group btn-group-sm" data-toggle="buttons">
@foreach($options as $option => $label)
    <label class="btn {{ $class }} {{ $option == $value ? 'active' : '' }}">
        <input id="{{ $id.$loop->iteration }}" type="radio" name="{{ $name }}" autocomplete="off" value="{{ $option }}" {{ $option == $value ? 'checked' : '' }} {!! $attributes !!}>
        {{ $label }}
    </label>
@endforeach
</div>

@script('bootstrap', 'radio'. $id)
$(':radio[id^="{{ $id }}"]').change(function(e) {
e.preventDefault();
$(e.target.form).submit();
});
@endscript