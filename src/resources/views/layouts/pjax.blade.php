<!-- ###PJAX### -->
<title>{{ $title }} - {{ config('app.name', '') }}</title>

<!-- Resources -->
{!! app('resources')->styles() !!}

<!-- Theme style -->
<link rel="stylesheet" href="{{ mix("css/adminlte.css") }}">

{!! app('resources')->scripts('header') !!}

{!! $content !!}

<script type="text/javascript">
if ($.fn.editable && $.fn.editable.defaults) {
    $.fn.editable.defaults.params = function (params) {
        params._token = "{{ csrf_token() }}";
        params._editable = 1;
        params._method = 'PUT';
        return params;
    };
}
</script>

{!! app('resources')->scripts() !!}