<form @if($id)id="{{ $id }}"@endif action="{{ $action }}" method="{{ $method }}" class="{{ $class }}" {!! $attributes !!}>
        @isset($fields['username'])
        <div class="form-group has-feedback {{ $fields['username']->hasError() ? 'has-error' : '' }}">
                @if($fields['username']->hasError())
                        <label class="control-label" for="{{ $fields['username']->getId() }}">
                                <i class="glyphicon glyphicon-remove-sign"></i>{{ $fields['username']->getError() }}</label></br>
                @endif
                {{ $fields['username']->attribute('autocomplete', 'off') }}
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        @endisset

        @isset($fields['password'])
        <div class="form-group has-feedback {{ $fields['password']->hasError() ? 'has-error' : '' }}">
                @if($fields['password']->hasError())
                        <label class="control-label" for="{{ $fields['password']->getId() }}">
                                <i class="glyphicon glyphicon-remove-sign"></i>{{ $fields['password']->getError() }}</label></br>
                @endif
                {{ $fields['password']->attribute('autocomplete', 'off') }}
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        @endisset

        @foreach($fields as $key => $field)
                @if(in_array($key, ['username','password','captcha']))
                        @continue;
                @endif
                @if($field instanceof \BaiSam\UI\Form\Field)
                        <div class="form-group has-feedback {{ $field->hasError() ? 'has-error' : '' }}">
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


        @if(isset($fields['captcha']) && $fields['captcha']->hasError())
        <div class="form-group has-feedback has-error" style="margin-bottom: 0px">
                <label class="control-label" for="inputError"><i class="glyphicon glyphicon-remove-sign"></i>{{ $fields['captcha']->getError() }}</label></br>
        </div>
        @endif

        <div class="row form-group">
                <div class="col-xs-8">
                        @isset($fields['captcha'])
                        <div class="{{ $fields['captcha']->hasError() ? 'has-error' : '' }}">
                                {{ $fields['captcha']->attribute('autocomplete', 'off')->attribute('required', true) }}
                        </div>
                        @endisset
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary btn-block btn-flat">立即登录</button>
                </div>
                <!-- /.col -->
        </div>
</form>