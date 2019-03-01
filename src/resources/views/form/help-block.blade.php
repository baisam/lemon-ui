<span class="help-block">
    @if(array_has($help, 'icon'))
    <i class="fa {{ array_get($help, 'icon') }}"></i>&nbsp;
    @endif
    {{ array_get($help, 'text', '') }}
</span>