<!-- Header Navbar -->
<nav @if($id)id="{{ $id }}"@endif class="navbar {{ $class }}" role="navigation" {!! $attributes !!}>
    <!-- Navbar header -->
    <div class="navbar-header">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        @if(isset($items['nav']) || isset($items['search']))
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-angle-double-down"></i>
        </button>
        @endif
    </div>

    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
    @isset($items['nav'])
        {{ $items['nav'] }}
    @endisset

    @isset($items['search'])
        {{ $items['search']->attribute('pjax-container', '')->addClass('navbar-left') }}
    @endisset
    </div>

    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        @if(isset($items['menu']))
            {{ $items['menu'] }}
        @endif
    </div>
</nav>