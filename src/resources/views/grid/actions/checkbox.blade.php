@if($single && count($options) === 1)
    <div class="btn-group btn-group-sm" data-toggle="buttons">
        <label class="btn {{ $class }} {{ array_keys($options)[0] == $value ? 'active' : '' }}">
            <input id="{{ $id }}" name="{{ $name }}" type="checkbox" autocomplete="off" value="{{ array_keys($options)[0] }}" {{ array_keys($options)[0] == $value ? 'checked' : '' }} {!! $attributes !!}>
            {{ array_values($options)[0] }}
        </label>
    </div>
@else
    <div class="btn-group btn-group-sm" data-toggle="buttons">
    @foreach($options as $option => $label)
        <label class="btn {{ $class }} {{ in_array($option, (array)$value) ? 'active' : '' }}">
            <input id="{{ $id.$loop->iteration }}" name="{{ $name }}[]" type="checkbox" autocomplete="off" value="{{ $option }}" {{ in_array($option, (array)$value) ? 'checked' : '' }} {!! $attributes !!}>
            {{ $label }}
        </label>
    @endforeach
    </div>
@endif

@script('bootstrap', 'checkbox_'. $id)
$(':checkbox[id^="{{ $id }}"]').change(function(e) {
    e.preventDefault();
    $(e.target.form).submit();
});
@endscript