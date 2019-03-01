@isset($header)
<!-- Content Header (Page header) -->
<section class="content-header clearfix">
    <h1>
        @if(is_array($header))
            {{ $header['title'] }}
            @unless(empty($header['subtitle']))
            <small>{{ $header['subtitle'] }}</small>
            @endunless
        @else
            {{ $header }}
        @endif
    </h1>

    @isset($breadcrumb)
        {{ $breadcrumb }}
    @endisset

    @isset($navigation)
        {{ $navigation }}
    @endisset
</section>
@endisset
<!-- Main content -->
<section class="content {{ $class }}" {!! $attributes !!}>
    @isset($help)
    <button type="button" class="btn btn-link btn-xs text-blue" data-toggle="collapse" data-target="#help-collapse" aria-expanded="false" aria-controls="help-collapse">
        <i class="fa fa-lightbulb-o"></i>&nbsp;操作提示
    </button>
    <div id="help-collapse" class="collapse callout callout-info no-print">
        <h4>操作提示</h4>
        <ul>
        @foreach($help as $h)
            <li>{{ $h }}</li>
        @endforeach
        </ul>
    </div>
    @endisset

    @include('ui::partials.error')

    {!! $content !!}

</section>
<!-- /.content -->

@hasSection('dialog')
    @yield('dialog')
    @stack('dialog')
@endif