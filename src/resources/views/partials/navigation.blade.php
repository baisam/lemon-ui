<ul @if($id)id="{{ $id }}"@endif class="nav {{ $class }}" {!! $attributes !!}>
@foreach($items as $item)
    @if (is_string($item) && $item === '##_SEPARATOR_##')
    <li role="separator" class="divider"></li>
    @elseif($item instanceof \BaiSam\UI\Layout\Component\DropDown)
    <li class="dropdown">{{ $item }}</li>
    @elseif($item instanceof \BaiSam\UI\Layout\Component\Link)
    <li @if($item->isActive())class="active" @endif>{{ $item }}</li>
    @else
    <li>{{ $item }}</li>
    @endif
@endforeach
</ul>