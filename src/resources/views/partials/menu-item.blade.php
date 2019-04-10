@if($label && isset($level))
<a href="{{ $url }}" nopjax>
    @if($icon)
    <i class="fa fa-{{ $icon }}"></i>
    @endif
    <span>{{ $label }}</span>
    <span class="pull-right-container">
        @if(count($items) > 0)
            <i class="fa fa-angle-left pull-right"></i>
        @endif
        @if($badge){{ $badge }}@endif
    </span>
</a>
@endif
@if(count($items) > 0)
<ul @if($id)id="{{ $id }}"@endif class="treeview-menu {{ $class }}" {!! $attributes !!}>
@foreach($items as $item)
@if (is_string($item) && $item === '##_SEPARATOR_##')
    <li role="separator" class="divider"></li>
@else
    <li @if($item->hasChildren())class="treeview"@elseif($item->isActive())class="active"@endif>{{ $item }}</li>
@endif
@endforeach
</ul>
@endif