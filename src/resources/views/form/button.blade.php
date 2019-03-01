<button id="{{ $id }}" type="{{ $type  }}" name="{{ $name }}" class="btn {{ $class }}" {!! $attributes !!}>
    @if($icon)
        <i class="fa fa-{{ $icon }}"></i>
    @endif
    {{ $content }}
</button>

@if($events)
@script('button', $id)
$(function() {
@foreach($events as $event => $callback)
  $('#{{ $id }}').on('{{ $event }}', function(e) {
    {!! $callback !!}
  });
@endforeach
});
@endscript
@endif