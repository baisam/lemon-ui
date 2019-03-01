<div class="switch {{ $class }}">
    <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="{{ $on['value'] }}" {{ $value==$on['value']?'checked':'' }} {!! $attributes !!}>
</div>
@script('switcher', $id)
$(function() {
    $('#{{ $id  }}').bootstrapSwitch({
        size: 'small',
        onText: '{{ $on['label'] }}',
        onColor: '{{ $on['color']?:'primary' }}',
        offText: '{{ $off['label'] }}',
        offColor: '{{ $off['color']?:'default' }}',
        onSwitchChange: function(event, state) {
            {!! isset($events['change']) ? $events['change'] : '' !!}
        }
    });
});
@endscript