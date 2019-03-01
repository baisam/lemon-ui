<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }} - {{ config('app.name', '江西佰尚信息技术有限公司') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Meta -->
    {!! $meta !!}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ mix("css/bootstrap.css") }}">
    <link rel="stylesheet" href="{{ mix("css/font-awesome.css") }}">
    <link rel="stylesheet" href="{{ mix("css/plugins/pace-flash.css") }}">

    <!-- Resources -->
    {!! app('resources')->styles() !!}

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ mix("css/adminlte.css") }}">


    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ mix("js/manifest.js") }}"></script>
    <script src="{{ mix("js/vendor.js") }}"></script>
    <script src="{{ mix("js/bootstrap.js") }}"></script>
    <script src="{{ mix("js/plugins/pace.js") }}"></script>

    {!! app('resources')->scripts('header') !!}

    <script src="{{ mix("js/adminlte.js") }}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    @include('ui::layouts.header')

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
        @if(isset($sidebar['left']))
            {{ $sidebar['left'] }}
        @endif
        </section>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">
        {!! $content !!}
    </div>
    <!-- /.content-wrapper -->

    @include('ui::layouts.footer')

    @foreach($footer as $item)
        {{ $item }}
    @endforeach
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ mix("js/plugins/jquery.pjax.js") }}"></script>
<script src="{{ mix("js/plugins/fastclick.js") }}"></script>
<script src="{{ mix("js/plugins/toastr.js") }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };

        $.pjax.defaults.timeout = 5000;
        $.pjax.defaults.maxCacheLength = 0;
        $.pjax.defaults.container = '#pjax-container';

        $(document).ajaxStart(function() {
            Pace.restart();
        });
    });

    $(document).on('click.pjax', 'a:not(a[target="_blank"],a[role="logout"],a[nopjax])', function (event) {
        $.pjax.click(event, {
            scrollTo: false
        });
    });

    $(document).on('submit.pjax', 'form[pjax-container]', function(event) {
        $.pjax.submit(event, {
            push: false,
            replace: false,
            scrollTo: false
        });
    });

    $(document).on("pjax:popstate", function() {
        $(document).one("pjax:end", function(event) {
            $(event.target).find("script[data-exec-on-popstate]").each(function() {
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
        });
    });

    $(document).on('pjax:send', function(xhr) {
        if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form[pjax-container] :submit');
            if($submit_btn) {
                $submit_btn.button('loading')
            }
        }
    });

    $(document).on('pjax:complete', function(xhr) {
        if(xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form[pjax-container] :submit');
            if($submit_btn) {
                $submit_btn.button('reset')
            }
        }
    });

</script>

{!! app('resources')->scripts() !!}

</body>
</html>