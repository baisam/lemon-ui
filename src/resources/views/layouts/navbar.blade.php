<!-- Header Navbar -->
<nav @if($id)id="{{ $id }}"@endif class="navbar {{ $class }}" role="navigation" {!! $attributes !!}>
    @foreach($items as $item)
        {{ $item }}
    @endforeach
</nav>