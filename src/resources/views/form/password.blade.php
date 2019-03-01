@if($prepend || $append)
<div class="input-group">
    @if($prepend)
    <span class="input-group-addon">{!! $prepend !!}</span>
    @endif
    <input id="{{ $id }}" type="{{ $input }}" name="{{ $name }}" value="" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>
    @if($append)
    <span class="input-group-addon">{!! $append !!}</span>
    @endif
</div>
@else
    <input id="{{ $id }}" type="{{ $input }}" name="{{ $name }}" value="" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>
@endif
