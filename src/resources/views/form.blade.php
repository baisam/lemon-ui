<form @if($id)id="{{ $id }}"@endif action="{{ $action }}" method="{{ $method }}" class="form-horizontal" pjax-container {!! $attributes !!}>
<div class="box @if($class) {{ $class }} @else box-primary @endif">
    <div class="box-header with-border">
        @isset($title)
            <h3 class="box-title">{{ $title }}</h3>
        @endisset
        <div class="box-tools">
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    {!! $content !!}

    @foreach($fields as $field)
        @if($field instanceof \BaiSam\UI\Form\Field)
        <div class="form-group form-group-sm {!! $field->hasError() ? 'has-error' : '' !!} @if($field->hasClass('hidden'))hidden @endif" id="form_group_{{ $field->getId() }}">
            @if($field->getLabel())
                <label class="col-sm-{{ isset($field->label_width) ? $field->label_width : (isset($label_width) ? $label_width : 2) }} control-label">
                    {{ $field->getLabel() }}
                    @if($field->isRequired())<span class="text-red">*</span>@endif
                </label>
                <div class="col-sm-{{ isset($field->content_width) ? $field->content_width : (isset($content_width) ? $content_width : 10) }}">
                    @if($field->hasError())
                        <label class="control-label" for="{{ $field->getId() }}"><i class="glyphicon glyphicon-remove-sign"></i>{{ $field->getError() }}</label></br>
                    @endif
                    {{ $field }}
                    @if(is_array($field->help()))
                        @component('ui::form.help-block', ['help' => $field->help()]) @endcomponent
                    @endif
                </div>
            @else
                <div class="col-sm-{{ isset($field->content_width) ? $field->content_width : (isset($content_width) ? $content_width : 10) }} col-sm-offset-{{ isset($field->label_width) ? $field->label_width : (isset($label_width) ? $label_width : 2) }}">
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

    {{ csrf_field() }}
    @foreach($hiddenFields as $field)
        {{ $field }}
    @endforeach
    </div>
    <!-- /.box-body -->

    <div class="box-footer clearfix">
        <div class="col-sm-{{ isset($content_width) ? $content_width : 10 }} col-sm-offset-{{ isset($label_width) ? $label_width : 2 }}">
            @if( isset($submitButton) )
                @php
                $submitButton->attribute('data-loading-text', new \Illuminate\Support\HtmlString("<i class='fa fa-spinner fa-spin '></i>". $submitButton->getLabel()) );
                $submitButton->addClass('margin-r-5');
                @endphp
                {{ $submitButton }}
            @endif

            @if( isset($resetButton) )
                @php($cancelButton->addClass('margin-r-5'))
                {{ $resetButton }}
            @endif

            @if( isset($cancelButton) )
                @php($cancelButton->addClass(['pull-left', 'margin-r-5']))
                {{ $cancelButton }}
            @endif
        </div>
    </div>
    <!-- /.box-footer -->
</div>
</form>
@if(isset($cancelButton))
@script
$('#{{ $cancelButton->getId() }}').click(function() {
    if ($(this).parents('.modal').length > 0) {
        $(this).parents('.modal:first').modal('hide');
    }
    else {
        $.pjax({
            url: "{!! URL::previous() !!}",
            push: false,
            replace: true,
            scrollTo: false
        });
    }
});
@endscript
@endif