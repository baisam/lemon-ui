<select id="{{ $id }}" name="{{ $name }}[]" class="form-control {{ $class }}"  multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >
    @foreach($options as $option => $label)
        <option value="{{ $option }}">{{ $label }}</option>
    @endforeach
</select>

@script
$(function() {
  $('#{{ $id }}').select2({tags: true, tokenSeparators: [',']});
});
@endscript