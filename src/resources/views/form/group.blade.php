@if(count($fields) > 0)
<fieldset id="{{ $id }}" class="{{ $class }}" {!! $attributes !!}>
    @isset($label)
    <legend>{{ $label }}</legend>
    @endisset
    <div class="hr-line-dashed"></div>
@foreach($fields as $field)
    @if($field instanceof \BaiSam\UI\Form\Field)
    <div class="form-group form-group-sm {!! $field->hasError() ? 'has-error' : '' !!}">
        @if($field->getLabel())
            <label class="col-sm-2 control-label">
                {{ $field->getLabel() }}
                @if($field->isRequired())<span class="text-red">*</span>@endif
            </label>
            <div class="col-sm-10">
                @if($field->hasError())
                    <label class="control-label" for="{{ $field->getId() }}"><i class="glyphicon glyphicon-remove-sign"></i>{{ $field->getError() }}</label></br>
                @endif
                {{ $field }}
                @if(is_array($field->help()))
                    @component('ui::form.help-block', ['help' => $field->help()]) @endcomponent
                @endif
            </div>
        @else
            <div class="col-sm-10 col-sm-offset-2">
                @if($field->hasError())
                    <label class="control-label" for="{{ $field->getId() }}"><i class="glyphicon glyphicon-remove-sign"></i>{{ $field->getError() }}</label></br>
                @endif
                {{ $field }}
                @if(is_array($field->help()))
                    @component('ui::form.help-block', ['help' => $field->help()]) @endcomponent
                @endif
            </div>
        @endif
    </div>
    @else
    {{ $field }}
    @endif
@endforeach
</fieldset>
@endif

@foreach($hiddenFields as $field)
    {{ $field }}
@endforeach