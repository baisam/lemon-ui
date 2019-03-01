<div class="input-group">
    <input id="{{ $id }}" type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>
    <span class="input-group-addon clearfix" style="padding: 1px;">
        <img id="{{$id}}_captcha" src="{{ captcha_src($config) }}" style="height:30px;cursor: pointer;"  title="点击刷新"/>
    </span>
</div>
@script
$('#{{ $id }}_captcha').click(function () {
    $(this).attr('src', $(this).attr('src')+'&'+Math.floor(Math.random()*(11)));
    $('#{{ $id }}').val('').focus();
});
@endscript