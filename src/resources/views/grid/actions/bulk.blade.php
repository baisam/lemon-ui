<select id="{{ $id }}" name="{{ $name }}" class="btn {{ $class }}" {!! $attributes !!} >
    <option>{{ $label }}</option>
    @foreach($options as $option => $label)
        <option value="{{ $option }}" {{ $option == $value ?'selected':'' }}>{{ $label }}</option>
    @endforeach
</select>
{{ $button }}