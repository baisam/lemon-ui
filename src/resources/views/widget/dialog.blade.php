@section('dialog')
    <!-- dialog -->
@hasSection('dialog-'. $id)
@elseif(empty($reference))
    @push('dialog')
    <div id="modal_{{ $id }}" class="modal" role="dialog">
        <div class="modal-dialog {{ $color }} {{ $size }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    @isset($title)
                    <h4 class="modal-title">{{ $title }}</h4>
                    @endisset
                </div>
                <div id="modal_{{ $id }}_pjax_content" class="modal-body {{ $class }}">
                    <!-- content -->
                </div>
                <div class="modal-footer">
                @switch($type)
                    @case('alert')
                        <button type="button" class="btn btn-default" data-dismiss="modal">确认</button>
                        @break
                    @case('confirm')
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button id="modal_{{ $id }}_ok" type="button" class="btn btn-primary">确认</button>
                        @break
                    @default
                        @isset($buttons['close'])
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ $buttons['close'] }}</button>
                            @unset($buttons['close'])
                        @endisset
                        @foreach($buttons as $btn_id => $btn)
                            @if($btn instanceof \BaiSam\UI\Form\Field\Button)
                                {{ $btn }}
                            @else
                                <button id="modal_{{ $id }}_{{ $btn_id }}" type="button" class="btn btn-primary">{{ $btn }}</button>
                            @endif
                        @endforeach
                @endswitch
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @endpush

    @script
    function dialog_{{ $id }}_show(refresh) {
        if (refresh) {
            $.pjax.reload({container: '#modal_{{ $id }}_pjax_content', url: refresh});
        }
        $('#modal_{{ $id }}').modal('show');
    }
    function dialog_{{ $id }}_hide() {
        $('#modal_{{ $id }}').modal('hide');
    }
    function dialog_{{ $id }}_bind() {
        $('#modal_{{ $id }}_pjax_content').find('[pjax-container]:not(data-pjax)')
            .attr('data-pjax', '#modal_{{ $id }}_pjax_content');
    }
    $('#modal_{{ $id }}').find('[data-dismiss="modal"]').click(function(e) {
        e.preventDefault();
        dialog_{{ $id }}_hide();

        return false;
    });
    $('#modal_{{ $id }}_pjax_content').on('pjax:beforeSend', function(e, xhr) {
        xhr.setRequestHeader('X-PJAX-Dialog', 'true');
    });
    $('#modal_{{ $id }}_pjax_content').on('pjax:success', function() {
        dialog_{{ $id }}_bind();
    });
    @endscript
@endif
@endsection
@section('dialog-'. $id)<!-- dialog -->@endsection

@php($iteration = isset($reference) ? 'ref'. $iteration : $iteration)
@script
function dialog_{{ $id }}_{{ $iteration }}_show({{ $args }}) {
    var html = @json($content), data;
    $('#modal_{{ $id }}').find('.modal-body').html(html);
    @if($url)
    // Load pjax content
    $.pjax({
        url: "{!! $url !!}",
        push: false,
        replace: true,
        scrollTo: false,
        container: '#modal_{{ $id }}_pjax_content'
    });
    $('#modal_{{ $id }}_pjax_content').off('pjax:beforeReplace').bind('pjax:beforeReplace', function(e, _d) {
        if (_d && _d[0] && $.trim(_d[0].nodeValue) != "###PJAX###") {
            try {
                data = JSON.parse(_d[0].nodeValue);
                data && dialog_{{ $id }}_hide();
            }
            catch(err) {
                data = _d[0].nodeValue;
            }
        }
    });
    @endif
    $('#modal_{{ $id }}').modal('show');
    dialog_{{ $id }}_bind();

@if($events)
    @isset($events['show'])
        @if($url)
        $('#modal_{{ $id }}_pjax_content').one('pjax:success', function() {
            {!! $events['show'] !!};
        });
        @else
        {!! $events['show'] !!};
        @endif
        @unset($events['show'])
    @endisset

    @isset($events['hide'])
    $('#modal_{{ $id }}').off('hide.bs.modal').on('hide.bs.modal', function (e) {
        var dismiss = true;
        {!! $events['hide'] !!};
        return dismiss;
    });
    @unset($events['hide'])
    @endisset

    @foreach($events as $event => $callback)
    $('#modal_{{ $id }}').find('#modal_{{ $id }}_{{ $event }}').off('click').on('click', function(e) {
        var dismiss = true;
        {!! $callback !!};

        if (dismiss) {
        $('#modal_{{ $id }}').off('hide.bs.modal').modal('hide');
        }

        return false;
    });
    @endforeach
@endif
}
@endscript

@if($show === 'js')
dialog_{{ $id }}_{{ $iteration }}_show({{ $args }});
@elseif($show)
@script
dialog_{{ $id }}_{{ $iteration }}_show({{ $args }});
@endscript
@endif