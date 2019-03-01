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

    <!-- Resources -->
    {!! app('resources')->styles() !!}

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ mix("css/adminlte.css") }}">


    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ mix("js/manifest.js") }}"></script>
    <script src="{{ mix("js/vendor.js") }}"></script>
    <script src="{{ mix("js/bootstrap.js") }}"></script>

    {!! app('resources')->scripts('header') !!}

    <script src="{{ mix("js/adminlte.js") }}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}" title="{{ config('app.name', '佰尚') }}"><b>{{ config('app.name', '佰尚') }}</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg text-primary">欢迎，请登录！</p>

        {!! $content !!}
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

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
    });

</script>

{!! app('resources')->scripts() !!}

</body>
</html>