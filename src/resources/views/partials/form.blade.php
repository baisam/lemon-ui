<form @if($id)id="{{ $id }}"@endif action="{{ $action }}" method="{{ $method }}" class="{{ $class }}" {!! $attributes !!}>
@foreach($fields as $field)
    @if($field instanceof \BaiSam\UI\Form\Field)
        <div class="form-group {{ $field->hasError() ? 'has-error' : '' }}">
        @if($field->getLabel())<label class="control-label">{{ $field->getLabel() }}</label>@endif
        @if($field->hasError())
        <label class="control-label" for="{{ $field->getId() }}">
                <i class="glyphicon glyphicon-remove-sign"></i>{{ $field->getError() }}</label></br>
        @endif
        {{ $field }}
        @if(is_array($field->help()))
        @component('ui::form.help-block', ['help' => $field->help()]) @endcomponent
        @endif
        </div>
    @else
        {{ $field }}
    @endif
@endforeach
</form>