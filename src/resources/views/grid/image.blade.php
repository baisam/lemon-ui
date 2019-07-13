@isset($url)<a href="{{ $url }}" @if($alt)alt="{{ $alt }}"@endif>@endisset
<img src="{{ $src }}" @isset($width)width="{{ $width }}"@endisset @isset($height)height="{{ $height }}"@endisset style="@isset($maxWidth)max-width: {{ $maxWidth }};@endisset @isset($maxHeight)max-height: {{ $maxHeight }};@endisset" @if($alt)alt="{{ $alt }}"@endif/>
@isset($url)</a>@endisset