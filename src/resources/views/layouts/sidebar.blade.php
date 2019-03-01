<!-- sidebar -->
<section class="sidebar sidebar-{{ $name }} {{ $class }}" {!! $attributes !!}>
@foreach($items as $item)
    {{ $item }}
@endforeach
</section>
<!-- /.sidebar -->