{{ $content }}
<div class="btn-group-xs @if($content)btn-group-hover @endif">
    @foreach($actions as $action)
        @if($action instanceof \BaiSam\UI\Layout\Component\DropDown)
            <div class="dropdown btn-group btn-group-xs">
                {{ $action }}
            </div>
        @else
        {{ $action }}
        @endif
    @endforeach
</div>
 