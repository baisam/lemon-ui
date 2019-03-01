@php
    $first_index = 0;
    $actions_index = -1;
    $select_rows_name = null;
    foreach ($visColumns as $index => $column) {
        if ($column->getType() == 'rowSelect') {
            $first_index = $index;
            $select_rows_name = $column->getName();
        }
        if ($column->getType() == 'actions') {
            $actions_index = $index;
            break;
        }
    }
    // 排序Url
    $order_url = remove_query_arg([isset($pager) ? $pager->getPageName() : 'page', '_token', 'op']);
    // 刷新Url
    $refresh_url = remove_query_arg(['_token', 'op']);
    // 分页Url
    $perpage_url = remove_query_arg(['perpage', '_token', 'op']);
@endphp

<div class="box @if($class) {{ $class }} @else box-primary @endif">
@if(isset($title) or isset($toolbar['top']))
    <div class="box-header with-border">
        @isset($title)
            <h3 class="box-title">{{ $title }}</h3>
        @endisset
        @isset($toolbar['top'])
            <div class="box-tools">
                <form action="{{ URL::current() }}" method="GET" pjax-container class="btn-group-sm">
                    {{ $toolbar['top'] }}
                </form>
            </div>
        @endisset
    </div>
@endif
<!-- /.box-header -->
    <div class="box-body">
        @if($filters)
            <div class="row">
                <form action="{{ URL::current() }}" method="GET" pjax-container>
                    @foreach($filters as $filter)
                        <div class="col-sm-{{ $filter->width() }}">
                            <div class="form-group form-group-sm">
                                @if($filter->getLabel())
                                    <label class="control-label" for="{{ $filter->getName() }}">{{ $filter->getLabel() }}</label>
                                @endif
                                <div>{{ $filter }}</div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-sm-1">
                        <div class="form-group form-group-sm">
                            @if($filters->first()->getLabel())
                                <label class="control-label">&nbsp;&nbsp;</label>
                            @endif
                            <div>
                                <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" data-loading-text="@if($filters->first()->getLabel()) &nbsp;&nbsp; @endif<i class='fa fa-spinner fa-spin'></i>"><strong>搜索</strong></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        @if(empty($untool))
        <form id="{{ $id }}_grid_tool_form" action="{{ URL::current() }}" method="GET" pjax-container>
            <ul class="list-inline clearfix" style="margin-bottom: 0;">
                <li class="btn-group-sm pull-right">
                    <small class="btn-box-tool">共
                        @if($pager instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                            {{ $pager->total() }}
                        @elseif(isset($pager))
                            {{ $pager->count() }}
                        @else
                            {{ $rows->count() }}
                        @endif 条数据
                    </small>
                    @isset($toolbar['right'])
                        @foreach($toolbar['right'] as $item)
                            {{ $item instanceof \BaiSam\UI\HtmlTag ? $item->addClass('btn-box-tool') : '' }}
                        @endforeach
                    @endisset
                    @if(isset($perPages))
                        <select class="btn btn-box-tool" data-toggle="perpage">
                            @if (is_array($perPages))
                                @foreach($perPages as $item)
                                    <option value="{{$item}}" {{$pager && $pager->perPage() == $item ? 'selected' : ''}}>{{$item}}</option>
                                @endforeach
                            @else
                                <option value="10" {{$pager && $pager->perPage() == 10 ? 'selected' : ''}}>10</option>
                                <option value="20" {{$pager && $pager->perPage() == 20 ? 'selected' : ''}}>20</option>
                                <option value="50" {{$pager && $pager->perPage() == 50 ? 'selected' : ''}}>50</option>
                                <option value="100" {{$pager && $pager->perPage() == 100 ? 'selected' : ''}}>100</option>
                            @endif
                        </select>
                        <small class="btn-box-tool">记录/页</small>
                    @endif
                    @if(isset($config['refresh']) && $config['refresh'])
                        <button type="button" class="btn btn-box-tool" data-toggle="refresh"><i class="fa fa-refresh"></i></button>
                    @endif
                    @isset($config['settings'])
                        <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#modal_{{ $id }}_grid_settings"><i class="fa fa-gear"></i></button>
                    @endisset
                </li>
                @foreach($toolbar['left'] as $item)
                    @if($item instanceof \BaiSam\UI\Layout\Component\DropDown)
                        <li class="dropdown btn-group-sm">
                            {{ $item }}
                        </li>
                    @elseif(is_string($item) && $item === '##_SEPARATOR_##')
                        <li role="separator" class="divider"></li>
                    @elseif($item)
                        <li class="btn-group-sm">
                            {{ $item }}
                        </li>
                    @endif
                @endforeach
            </ul>
        </form>
        @endif

        <div class="">
            <table @if($id)id="{{ $id }}"@endif class="table @if(count($headers) > 1)table-bordered @endif table-striped table-condensed table-hover" {!! $attributes !!}>
                <thead>
                @foreach($headers as $index => $header)
                    <tr>
                        @foreach($header as $column)
                            @if(is_array($column) && isset($column['columns']))
                                <th colspan="{!! count($column['columns']) !!}" class="complex">{{ $column['title'] }}</th>
                            @elseif($column->isVisible())
                                @if($column->sorting())
                                    <th class="@if(is_array($column->sorting())) sorting_{{ strtolower($column->sorting()[1]) }} @else sorting @endif" @if(count($headers) > 1 && $index === 0)rowspan="{!! count($headers) !!}" @endif @if($column->width())width="{{ $column->width() }}" @endif>
                                        <a href="{{ add_query_arg(is_array($column->sorting()) ? ['order' => $column->sorting()[0], 'orderby' => $column->sorting()[1] == 'ASC' ? 'desc' : 'asc'] : ['order' => $column->sorting(), 'orderby' => 'asc'], $order_url) }}" pjax-container>{{ $column->getTitle()  }}</a>
                                    </th>
                                @else
                                    <th @if(count($headers) > 1 && $index === 0)rowspan="{!! count($headers) !!}" @endif @if($column->width())width="{{ $column->width() }}" @endif>
                                        {{ $column->getTitle()  }}
                                    </th>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </thead>

                <tbody>
                @forelse($rows as $row)
                    <tr id="{{ $id }}_row_{{ $row->getMajorKey() }}">
                        <td>
                            @foreach($row as $i => $cell)
                                {{ $cell }}
                                @if($i < count($row) -1 && $row[$i+1]->isVisible())
                        </td>
                        <td>
                            @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100">{{ $emptyString }}</td>
                    </tr>
                @endforelse
                </tbody>

                <tfoot>
                @if(count($rows) > 10)
                    <tr>
                        @foreach($visColumns as $column)
                            <th>{{ $column->getTitle() }}</th>
                        @endforeach
                    </tr>
                @endif
                </tfoot>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        @if(isset($pager) && $pager->hasPages())
            <div class="dataTables_wrapper row">
                <div class="col-sm-4">
                    <div class="dataTables_info">
                        @if($pager instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                            显示 {{ $pager->total() }} 条数据中的 {{ $pager->perPage()*($pager->currentPage()-1)+1 }} 到 {{ $pager->currentPage() >= $pager->lastPage() ? $pager->total() : $pager->perPage()*$pager->currentPage() }} 条
                        @else
                            显示{{ $pager->count() }}条数据中的{{ $pager->perPage()*($pager->currentPage()-1)+1 }}到{{ $pager->perPage()*($pager->currentPage()-1)+$rows->count() }}条
                        @endif
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="dataTables_paginate">
                        {{ $pager->render('ui::partials.pagination') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- /.box-footer -->
</div>
@isset($config['settings'])
    <div id="modal_{{ $id }}_grid_settings" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">选项设置</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($visColumns as $index => $column)
                            @if($index > 0 && $index != $actions_index)
                                <div class="col-sm-3">
                                    <label class="checkbox-inline"><input type="checkbox" name="visible_columns" value="{{ $index }}" checked>{{ $column->getTitle() }}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="apply_grid_settings" class="btn btn-primary pull-left">应 用</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endisset

@php
    $config = array_merge([
        'responsive'    => true,
        'stateSave'     => true,
        'autoWidth'     => false,
        'orderMulti'    => false,
        'processing'    => true,
        'searching'     => false,
        'ordering'      => false,
        'paging'        => false,
        'info'          => false
    ], $config);

    // 设置可见性优先级
    $config['columnDefs'] = [['responsivePriority' => 1, 'targets' => $first_index]];
    if ($actions_index > 0) {
        $config['columnDefs'][] = ['responsivePriority' => 2, 'targets' => $actions_index];
    }
    if (isset($fixedColumn)) {
        unset($config['responsive']);
        // 自动设置固定列
        $config['scrollX'] = true;
        $config['scrollCollapse'] = true;
        if (is_string($fixedColumn) || $fixedColumn === 1) {
            $config['fixedColumns'] = true;
        }
        else {
            $visCount = count($visColumns);
            if ($fixedColumn > ceil($visCount / 2)) {
                $config['fixedColumns'] = [
                    'leftColumns' => 0,
                    'rightColumns' => $visCount - $fixedColumn + 1
                ];
            }
            else {
                $config['fixedColumns'] = [
                    'leftColumns' => $fixedColumn
                ];
            }
        }
    }

    // 行选择 select: {style: 'multi'} single os
    if (isset($config['select']) || isset($select_rows_name)) {
        if (!isset($config['select'])) {
            $config['select'] = ['style' => 'multi', 'selector' => 'td:first-child :checkbox'];
        }
        else if (is_string($config['select'])) {
            $config['select'] = ['style' => $config['select'], 'selector' => 'td:first-child :checkbox'];
        }

        if (isset($select_rows_name) && isset($config['responsive'])) {
            $config['responsive'] = [
                'details' => [
                    'type' => 'column',
                    'target' => $first_index + 1
                ]
            ];
        }
    }

    //TODO 拖拽行排序 rowReorder   colReorder: true
    if (isset($config['rowReorder'])) {
        unset($config['responsive']);
    }
    if (isset($config['colReorder'])) {
        $config['colReorder'] = ['realtime' => false];
    }


    // 列显控制
    $config['columns'] = [];
    foreach ($visColumns as $index => $column) {
        $config['columns'][] = [
            'className' => (isset($select_rows_name) && $first_index + 1 === $index ? 'control' : ''),
            'orderable' => false !== $column->sorting(),
            'data'      => $column->getName()
        ];
    }

    // 加载资源文件
    if (isset($config['responsive'])) {
        app('resources')->getInstance()->requireResource('datatable.responsive');
    }
    if (isset($config['select'])) {
        app('resources')->getInstance()->requireResource('datatable.select');
    }
    if (isset($config['rowReorder'])) {
        app('resources')->getInstance()->requireResource('datatable.rowReorder');
    }
    if (isset($config['colReorder'])) {
        app('resources')->getInstance()->requireResource('datatable.colReorder');
    }
    if (isset($config['keys'])) {
        app('resources')->getInstance()->requireResource('datatable.keyTable');
    }

    $config['language'] = array_merge([
        'processing' => '处理中...',
        'emptyTable' => '表中数据为空',
        'loadingRecords' => '请稍等，载入中...',
        'zeroRecords' => '没有要显示的记录'
    ], array_wrap($config['language']??[]));
@endphp

@script
$('#{{ $id }}_grid_tool_form .pull-right').find('.btn-default').removeClass('btn-default');

$(function() {
var _{{ $id }}_config = {!! json_encode($config) !!};
var _{{ $id }}_table = $('#{{ $id }}').dataTable(_{{ $id }}_config);
new $.fn.dataTable.FixedHeader( _{{ $id }}_table );

var _{{ $id }}_table_api = new $.fn.dataTable.Api( _{{ $id }}_table );

var _uniqid_t = new Date().getTime();
$(document).on('pjax:beforeReplace.'+ _uniqid_t, function(xhr, settings) {
    if ($('#{{ $id }}').parents('#'+ $(xhr.target).attr('id')).length > 0) {
        _{{ $id }}_table_api.destroy(true);
        $(document).off('pjax:beforeReplace.'+ _uniqid_t);
    }
});

@if(isset($config['select']) && isset($select_rows_name))
$('#{{ $id }} :checkbox[name="{{ $select_rows_name }}_all"]').change(function() {
    if ($(this).is(':checked')) {
        _{{ $id }}_table_api.rows().select();
    }
    else {
        _{{ $id }}_table_api.rows().deselect();
    }
});
_{{ $id }}_table_api.on( 'select', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
        dt.rows( indexes ).data().pluck( '{{ $select_rows_name }}' ).each(function(id) {
            id = $(id).val();
            $('#{{ $id }}_grid_tool_form').find(':hidden[name="{{ $select_rows_name }}[]"][value="'+id+'"]').remove();
            $('#{{ $id }}_grid_tool_form').append('<input type="hidden" name="{{ $select_rows_name }}[]" value="'+id+'"/>');
            $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}[]"][value="'+id+'"]').prop('checked', true);
        });

        $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}_all"]').prop('checked',
            $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}[]"]:checked').length == $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}[]"]').length);
        $('#{{ $id }}_grid_tool_form').find(':submit[select-rows]').prop('disabled', false);
    }
} );
_{{ $id }}_table_api.on( 'deselect', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
        dt.rows( indexes ).data().pluck( '{{ $select_rows_name }}' ).each(function(id) {
            id = $(id).val();
            $('#{{ $id }}_grid_tool_form').find(':hidden[name="{{ $select_rows_name }}[]"][value="'+id+'"]').remove();
            $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}[]"][value="'+id+'"]').prop('checked', false);
        });

        $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}_all"]').prop('checked', false);
        $('#{{ $id }}_grid_tool_form').find(':submit[select-rows]').prop('disabled', $('#{{ $id }} :checkbox[name="{{ $select_rows_name }}[]"]:checked').length == 0);
    }
} );

$('#{{ $id }}_grid_tool_form').find(':submit[select-rows]').click(function() {
    var count = $('#{{ $id }}_grid_tool_form').find(':hidden[name="{{ $select_rows_name }}[]"]').length;
    if (count == 0) {
        alert($('#{{ $id }}').attr('select-message') || '请选择数据项！');
        return false;
    }

    if ($(this).attr('confirm') && ! confirm($(this).attr('confirm'))) {
        return false;
    }

    $('#{{ $id }}_grid_tool_form').append('{{ csrf_field() }}');
    $('#{{ $id }}_grid_tool_form').append('<input type="hidden" name="op" value="'+$(this).attr('id')+'"/>');
    @if(isset($pager) && $pager->hasPages())
        $('#{{ $id }}_grid_tool_form').append('<input type="hidden" name="{{ $pager->getPageName() }}" value="{{ $pager->currentPage() }}"/>');
    @endif
}).prop('disabled', true);
@endif

$('#{{ $id }}_grid_tool_form').find(':submit:not([select-rows])').click(function() {
    if ($(this).attr('confirm') && ! confirm($(this).attr('confirm'))) {
        return false;
    }

    $('#{{ $id }}_grid_tool_form').append('{{ csrf_field() }}');
    $('#{{ $id }}_grid_tool_form').append('<input type="hidden" name="op" value="'+$(this).attr('id')+'"/>');
});

@isset($config['settings'])
_{{ $id }}_table_api.columns().flatten().each( function ( colIdx ) {
    $('#modal_{{ $id }}_grid_settings')
    .find('input:checkbox[name="visible_columns"][value='+colIdx+']')
    .prop('checked', _{{ $id }}_table_api.column(colIdx).visible());
} );

$('#modal_{{ $id }}_grid_settings').find('button[name="apply_grid_settings"]').click(function(e) {
    e.preventDefault();

    $('#modal_{{ $id }}_grid_settings')
    .find('input:checkbox[name="visible_columns"]').each(function() {
        _{{ $id }}_table_api.column($(this).val()).visible($(this).prop('checked'));
    });

    $('#modal_{{ $id }}_grid_settings').modal('hide');
});
@endisset
});
@endscript

@if(isset($perPages))
@script('grid')
$('#{{ $id }}_grid_tool_form [data-toggle="perpage"]').on('change', function(e) {
    e.preventDefault();
    var url = '{!! $perpage_url !!}';
    url += (url.indexOf('?')<0 ? '?' : '&') + 'perpage=' + $(e.currentTarget).val();

    if ($(this.form).attr('data-pjax')) {
        $.pjax.reload({container: $(this.form).attr('data-pjax'), url: url});
    }
    else {
        $.pjax.reload({url: url});
    }
});
@endscript
@endif

@if(isset($config['refresh']) && $config['refresh'])
@script('grid')
$('#{{ $id }}_grid_tool_form [data-toggle="refresh"]').on('click', function(e) {
    e.preventDefault();

    if ($(this.form).attr('data-pjax')) {
        $.pjax.reload({container: $(this.form).attr('data-pjax'), url: '{!! $refresh_url !!}'});
    }
    else {
        $.pjax.reload({url: '{!! $refresh_url !!}'});
    }
});
@endscript
@endif

@if($actions_index > -1)
<form id="{{ $id }}_patch_form" action="{{ isset($patchUrl) ? url($patchUrl) : $refresh_url }}" method="post" pjax-container>
    {{ method_field('') }}
    {{ csrf_field() }}
    <input type="hidden" name="op">
    <input type="hidden" name="id">
</form>
@script('grid')
function {{ $id }}_put_submit(id, message) {
    if ( message !== undefined && !confirm(message)) {
        return;
    }

    $('#{{ $id }}_patch_form').find(':hidden[name=_method]').val('PUT');
    $('#{{ $id }}_patch_form').find(':hidden[name=op]').val('put');
    $('#{{ $id }}_patch_form').find(':hidden[name=id]').val(id);
    $('#{{ $id }}_patch_form').submit();
}
function {{ $id }}_delete_submit(id, message) {
    if ( message !== undefined && !confirm(message)) {
        return;
    }

    $('#{{ $id }}_patch_form').find(':hidden[name=_method]').val('DELETE');
    $('#{{ $id }}_patch_form').find(':hidden[name=op]').val('delete');
    $('#{{ $id }}_patch_form').find(':hidden[name=id]').val(id);
    $('#{{ $id }}_patch_form').submit();
}
function {{ $id }}_patch_submit(op, id, message) {
    if ( message !== undefined && !confirm(message)) {
        return;
    }

    $('#{{ $id }}_patch_form').find(':hidden[name=_method]').val('PATCH');
    $('#{{ $id }}_patch_form').find(':hidden[name=id]').val(id);
    $('#{{ $id }}_patch_form').find(':hidden[name=op]').val(op);
    $('#{{ $id }}_patch_form').submit();
}
@endscript
@endif

@script('grid', 'dropdownHover')
;(function($, window, undefined) {
    // outside the scope of the jQuery plugin to
    // keep track of all dropdowns
    var $allDropdowns = $();
    // if instantlyCloseOthers is true, then it will instantly
    // shut other nav items when a new one is hovered over
    $.fn.dropdownHover = function(options) {
        // the element we really care about
        // is the dropdown-toggle's parent
        $allDropdowns = $allDropdowns.add(this.parent());

        return this.each(function() {
            var $this = $(this).parent(),
            defaults = {
                delay: 500,
                instantlyCloseOthers: true
            },
            data = {
                delay: $(this).data('delay'),
                instantlyCloseOthers: $(this).data('close-others')
            },
            options = $.extend(true, {}, defaults, options, data),
            timeout;

            $this.hover(function() {
                if(options.instantlyCloseOthers === true)
                    $allDropdowns.removeClass('open');
                window.clearTimeout(timeout);
                $(this).addClass('open');
            }, function() {
                timeout = window.setTimeout(function() {
                    $this.removeClass('open');
            }, options.delay);
        });
    });
};

$('table [data-toggle="dropdown"]').dropdownHover();
})(jQuery, this);
@endscript