<input type="checkbox" name="{{ $name }}" class="grid-switch-{{ $name }}" {{ $value == $on['value']?'checked':'' }} />
@script('switch', $name)
$(function() {
    $('.grid-switch-{{ $name }}').bootstrapSwitch({
        size:'mini',
        onText: '{{ $on['label'] }}',
        onColor: '{{ $on['color']?:'primary' }}',
        offText: '{{ $off['label'] }}',
        offColor: '{{ $off['color']?:'default' }}'
    });
});
@endscript