<a @if($id)id="{{ $id }}"@endif href="#" class="dropdown-toggle {{ $class }}" data-toggle="dropdown" {!! $attributes !!}>
@if($icon)
    <i class="fa fa-{{ $icon }}"></i>
@endif
@if(is_string($title))
    {{ $title }}<span class="caret"></span>
@else
    {{ $title }}
@endif
</a>
<ul class="dropdown-menu">
@foreach($items as $item)
    @if(is_string($item) && $item === '##_SEPARATOR_##')
    <li role="separator" class="divider"></li>
    @else
    <li>{{ $item }}</li>
    @endif
@endforeach
</ul>