<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo" nopjax>
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{{ config('app.name', '佰尚') }}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{ config('app.name', '佰尚信息系统') }}</span>
    </a>
    <!-- Header Navbar -->
    @if(isset($navbar['navbar']))
        {{ $navbar['navbar']->addClass('navbar-static-top') }}
    @endif
</header>