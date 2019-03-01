<div id="{{ $id }}" class="box {{ $class }}" {!! $attributes !!}>
    <div class="box-header with-border">
        @isset($icon)
        <i class="fa fa-{{ $icon }}"></i>
        @endisset
        <h3 class="box-title">{{ $title }}</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        {!! $content !!}
    </div>
    <!-- /.box-body -->

</div>
<!-- /.box -->