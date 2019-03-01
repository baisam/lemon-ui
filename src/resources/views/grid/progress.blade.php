<div class="progress {{ $size }} @if($vertical)vertical @endif @if($active)active @endif">
    <div class="progress-bar {{ $color }} @if($striped)progress-bar-striped @endif" role="progressbar" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="{{ $max }}" style="@if($vertical)height @else width @endif :{{ $percent }}%">
        @if($label) {{ $label }} @else <span class="sr-only">{{ $percent }}% Complete</span> @endif
    </div>
</div>