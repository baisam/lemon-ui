<textarea id="{{ $id }}" name="{{ $name }}" class="form-control {{ $class }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ $value }}</textarea>
@script
$(function() {
  CKEDITOR.replace('{{ $id }}');
});
@endscript

@script

function getCKEditorData() {
  $('#{{ $id }}').val(CKEDITOR.instances.{{ $id }}.getData())
}

@endscript