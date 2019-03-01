<a @if($id)id="{{ $id }}"@endif href="{{ $url }}" @if($title)title="{{ $title }}"@endif @if($class)class="{{ $class }}"@endif {!! $attributes !!}>
    @if($icon)
    <i class="fa fa-{{ $icon }}"></i>
    @endif
    {{ $content }}
</a>