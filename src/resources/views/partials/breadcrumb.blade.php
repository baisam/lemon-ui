<ol class="breadcrumb {{$class}}" {!! $attributes !!}>
    @foreach($items as $item)
    <li @if(is_string($item)) class="active"@endif>{{ $item }}</li>
    @endforeach
</ol>