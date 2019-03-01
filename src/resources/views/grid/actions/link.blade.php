<a @if($id)id="{{ $id }}"@endif href="{{ $url }}" @if($title)title="{{ $title }}"@endif class="btn {{ $class }}" {!! $attributes !!}>
    @if($icon)
    <i class="fa fa-{{ $icon }}"></i>
    @endif
    {{ $content }}
</a>