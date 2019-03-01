<div id="{{ $id }}" class="btn-group">
    <div class="input-group input-group-sm" style="max-width: 20rem">
        @if(count($options) > 0)
        <div class="input-group-btn">
            <select name="{{ $name }}_choice" class="btn {{ $class ?:'btn-default' }}">
                @foreach($options as $option => $label)
                    <option value="{{ $option }}" {{ $option == Request::get($name.'_choice') ?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control pull-right {{ $class }}" {!! $attributes !!}>

        <div class="input-group-btn">
            {{ $button->attribute('data-loading-text', new \Illuminate\Support\HtmlString("<i class='fa fa-spinner fa-spin '></i>")) }}
        </div>
    </div>
</div>