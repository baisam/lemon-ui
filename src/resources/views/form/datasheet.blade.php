@php
    $first_index = 0;
    $actions_index = -1;
    foreach ($visColumns as $index => $column) {
        if ($column->getType() == 'actions') {
            $actions_index = $index;
            break;
        }
    }
@endphp

<div class="box @if($class) {{ $class }} @else box-primary @endif">
@if(isset($title))
    <div class="box-header with-border">
        @isset($title)
            <h3 class="box-title">{{ $title }}</h3>
        @endisset
    </div>
@endif
<!-- /.box-header -->
    <div class="box-body no-padding">
        <table @if($id)id="{{ $id }}"@endif class="table table-bordered table-hover" {!! $attributes !!}>
            <thead>
                <tr>
                    @foreach($visColumns as $column)
                    <th @if($column->width())width="{{ $column->width() }}" @endif>
                        {{ $column->getTitle()  }}
                        @if(isset($fields[$column->getName()]) && $fields[$column->getName()]->isRequired())
                        <span class="text-red">*</span>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
            @foreach($rows as $row)
                <tr id="{{ $id }}_row_{{ $row->getMajorKey()  }}">
                    @foreach($row as $i => $cell)
                    <td>
                        {{ $cell }}
                        @if($cell->rawData() instanceof \BaiSam\UI\Form\Field && $cell->rawData()->hasError())
                            <div class="form-group  form-group-sm has-error">
                                <label class="control-label" for="{{ $cell->rawData()->getId() }}"><i class="glyphicon glyphicon-remove-sign"></i>{{ $cell->rawData()->getError() }}</label>

                            </div>
                        @endif
                    </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        @foreach($visColumns as $column)
            @if(isset($fields[$column->getName()]) && is_array($fields[$column->getName()]->help()))
            <div class="row">
                <div class="col-sm-12">
                    @component('ui::form.help-block', ['help' => $fields[$column->getName()]->help()]) @endcomponent
                </div>
            </div>
            @endif
        @endforeach
    </div>
    <!-- /.box-footer -->
</div>

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

    // 列显控制
    $config['columns'] = [];
    foreach ($visColumns as $index => $column) {
        $column = [
            'orderable' => false !== $column->sorting(),
            'data'      => $column->getName()
        ];

        $config['columns'][] = $column;
    }

    // 加载资源文件
    if (isset($config['responsive'])) {
        app('resources')->getInstance()->requireResource('datatable.responsive');
    }

    $config['language'] = array_merge([
        'processing' => '处理中...',
        'emptyTable' => '表中数据为空',
        'loadingRecords' => '请稍等，载入中...',
        'zeroRecords' => '没有要显示的记录'
    ], array_wrap($config['language']??[]));

    $alert = new \BaiSam\UI\Widgets\Dialog($id .'_alert', '提示信息');
    $confirm = new \BaiSam\UI\Widgets\Dialog($id .'_confirm', '确认信息');
@endphp

@script
$(function() {
    var _{{ $id }}_config = {!! json_encode($config) !!};
    _{{ $id }}_config.columns[{{ $first_index }}].render = function(data, type, row, meta) {
        var startIndex = meta.settings._iDisplayStart;
        return '<label class="form-control-static">'+ (startIndex+meta.row+1) +'.</label>'+ data;
    };
    _{{ $id }}_config.columns[{{ $actions_index }}].render = function(data, type, row, meta) {
        return '<div class="form-control-static text-nowrap">'+data+'</div>';
    };
    for(var i = 0; i < _{{ $id }}_config.columns.length; i++) {
        if ({{ $first_index }} != i && {{ $actions_index }} != i) {
            _{{ $id }}_config.columns[i].render = function(data, type, row, meta) {
                return data;
            };
        }
    }

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

    var _{{ $id }}_template = $( _{{ $id }}_table_api.data() ).last();

    var _{{ $id }}_table_number = _{{ $id }}_table_api.page.info().recordsTotal;

    var _{{ $id }}_table_data = _{{ $id }}_table_api.data();
    _{{ $id }}_table_data.pop();
    _{{ $id }}_table_api.clear();
    _{{ $id }}_table_api.rows.add(_{{ $id }}_table_data).draw();

    if (_{{ $id }}_table_number == 1) {
        var newrow = {{ $id }}_table_newrow( '___new__'+ _{{ $id }}_table_number++ );
        _{{ $id }}_table_api.row.add(newrow).draw();
    }

    function {{ $id }}_sync_datatable() {
        var items = _{{ $id }}_table_api.data();
        $('#{{ $id }}').find('tr[id^="{{ $id }}_row_"]').each(function(){
            var _self = this
            var index = _{{ $id }}_table_api.row($(_self)).index();
            var item = items[index];

            for(i in _{{ $id }}_config.columns) {
                var id = '#'+$(item[_{{ $id }}_config.columns[i].data]).attr('id');
                //items[index][_{{ $id }}_config.columns[i].data] = $(item[_{{ $id }}_config.columns[i].data]).filter('[value]')
                //    .attr('value', $(_self).find(id).val())
                //    .val($(_self).find(id).val())
                //    .appendTo('<div></div>').html();
            }
        });
        //_{{ $id }}_table_api.clear();
        //_{{ $id }}_table_api.rows.add(items).draw();
    }

    function {{ $id }}_table_newrow(id) {
        var newrow = {};
        for(i in _{{ $id }}_config.columns) {
            newrow[_{{ $id }}_config.columns[i].data] = (_{{ $id }}_template[0][_{{ $id }}_config.columns[i].data]+'')
                .replace(new RegExp('___new__'+ _{{ $id }}_config.columns[i].data, "g"), '_'+ id +'_'+ _{{ $id }}_config.columns[i].data)
                .replace(new RegExp('\\[__new_\\]\\['+ _{{ $id }}_config.columns[i].data +'\\]',"g"), '['+ id +']['+ _{{ $id }}_config.columns[i].data +']');

            if ({{ $first_index }} == i) {
                newrow[_{{ $id }}_config.columns[i].data] = newrow[_{{ $id }}_config.columns[i].data].replace(' value="__new_"', 'value="'+ id +'"');
            }
        }

        return newrow;
    }

    // 增加
    $('#{{ $id }}').on('click', 'button[name="insert"]', function(e) {
        var _self = this
        e.preventDefault();
        {{ $id }}_sync_datatable();

        if (_{{ $id }}_table_api.page.info().recordsTotal >= {{ isset($max_items_num) ? $max_items_num : 50 }}) {
            @php
                echo $alert->alert('不能再添加了！')
                ->size(\BaiSam\UI\UIRepository::STYLE_SIZE_SMALL)
                ->showJs();
            @endphp
            return;
        }

        var newrow = {{ $id }}_table_newrow( '___new__'+ _{{ $id }}_table_number++ );
        var new_index = _{{ $id }}_table_api.row.add(newrow).index();

        var index = _{{ $id }}_table_api.row($(_self).parents('tr')).index();
        var items = _{{ $id }}_table_api.data();
        _{{ $id }}_table_api.clear();

        items.splice(index+1, 0, items.splice(new_index, 1)[0]);
        _{{ $id }}_table_api.rows.add(items).draw();
    });

    // 删除
    $('#{{ $id }}').on('click', 'button[name="delete"]', function(e) {
        var _self = this
        e.preventDefault();
        {{ $id }}_sync_datatable();

        if (_{{ $id }}_table_api.page.info().recordsTotal == 1) {
            @php
            echo $alert->alert('不能删除了！')
            ->size(\BaiSam\UI\UIRepository::STYLE_SIZE_SMALL)
            ->showJs();
            @endphp
            return;
        }
        @php
            echo $confirm->confirm(isset($confirm_message) ? $confirm_message : '确定要删除吗？')
            ->on('ok', "_{$id}_table_api.row($(_self).parents('tr')).remove().draw()")
            ->args('_self', "_{$id}_table_api")
            ->size(\BaiSam\UI\UIRepository::STYLE_SIZE_SMALL)
            ->showJs();
        @endphp
    });

    // 上移
    $('#{{ $id }}').on('click', 'button[name="up"]', function(e) {
        var _self = this
        e.preventDefault();
        {{ $id }}_sync_datatable();

        var index = _{{ $id }}_table_api.row($(_self).parents('tr')).index();
        if ((index - 1) >= 0) {
            var items = _{{ $id }}_table_api.data();
            _{{ $id }}_table_api.clear();

            items.splice((index - 1), 0, items.splice(index, 1)[0]);
            _{{ $id }}_table_api.rows.add(items).draw();
        }
    });

    // 下移
    $('#{{ $id }}').on('click', 'button[name="down"]', function(e) {
        var _self = this
        e.preventDefault();
        {{ $id }}_sync_datatable();

        var index = _{{ $id }}_table_api.row($(_self).parents('tr')).index();
        var max = _{{ $id }}_table_api.rows().data().length;
        if ((index + 1) < max) {
            var items = _{{ $id }}_table_api.data();

            _{{ $id }}_table_api.clear();
            items.splice((index + 1), 0, items.splice(index, 1)[0]);
            _{{ $id }}_table_api.rows.add(items).draw();
        }
    });
});
@endscript

