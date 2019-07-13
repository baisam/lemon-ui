<input id="{{ $id }}" type="file" class="{{ $class }}" name="{{ $name }}[]" {!! $attributes !!} />
<input type="hidden" name="_{{ $name }}" value="{{ $uploadKey }}" />
@isset($deleteUrl)
@section('dialog')
    <!-- dialog -->
    @hasSection('dialog-file-confirm')
    @else
        @push('dialog')
            <div id="modal_file_confirm" class="modal" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">操作提示</h4>
                        </div>
                        <div class="modal-body">
                            <!-- content -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button id="modal_file_confirm_ok" type="button" class="btn btn-primary">确认</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        @endpush
    @endif
@endsection
@section('dialog-file-confirm')<!-- dialog -->@endsection
@endisset

@php
    $config = array_merge([
        'language' => 'zh',
        'showPreview' => false,
        'showCaption' => true,
        'showRemove'  => true,
        'showUpload'  => false,
        'showDrag'    => false,
        'showZoom'    => false,
        'uploadAsync' => true,
        'resizeImage' => false,
        'purifyHtml'  => true,
        'maxFilePreviewSize' => 10240, // 10M
        'allowedPreviewTypes' => ['image', 'html', 'text', 'video', 'audio', 'flash'],
        'removeFromPreviewOnError' => true,
        'layoutTemplates' => [
            'actions' => '{drag}' .
    '<div class="file-actions">' .
    '    <div class="file-footer-buttons">' .
        '         {upload} {delete} {zoom} {other}' .
        '    </div>' .
    '    <div class="clearfix"></div>' .
    '</div>'
        ],
        'otherActionButtons' => '<input type="hidden" name="'. e($name) .'[]" {dataKey} value="##GUID##" />',
        'browseLabel' => '浏览',
        'removeLabel' => '删除',
        'uploadLabel' => '上传',
        'msgFilesTooMany' => '选择上传的文件数量({n}) 超过允许的最大数值{m}！'
    ], $config);
    $config['mainClass'] = 'input-group-sm';
    // 默认关闭删除按钮
    $config['initialPreviewShowDelete'] = false;
    // 启用已上传文件计数
    $config['validateInitialCount'] = true;
    $config['overwriteInitial'] = false;
    $config['ajaxSettings'] = [
        'headers' => [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'X-CSRF-TOKEN' => csrf_token()
        ]
    ];
    if (isset($uploadUrl)) {
        $config['showUpload'] = true;
        $config['uploadUrl'] = url($uploadUrl);
        $config['uploadExtraData'] = [
            '_method'   => 'PUT',
            'field'     => $key,
            'uploadKey' => $uploadKey
        ];
    }
    if (isset($deleteUrl)) {
        $config['ajaxDeleteSettings'] = [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
                'X-CSRF-TOKEN' => csrf_token()
            ]
        ];
        $config['showDelete'] = true;
        $config['deleteUrl'] = url($deleteUrl);
        $config['deleteExtraData'] = [
            '_method'   => 'DELETE'
        ];
    }
    if (isset($previewUrl)) {
        $config['initialPreviewAsData'] = true;
        $config['showPreview'] = $previewUrl ? true : false;
        $config['showZoom'] = $previewUrl ? true : false;
    }

    //TODO 如果有文件数据，则打开预览功能.

    if (!isset($config['fileActionSettings'])) {
        $config['fileActionSettings'] = [];
    }
    $config['fileActionSettings'] = array_merge(array_only($config, [
        'showDelete', 'showRemove', 'showUpload', 'showZoom', 'showDrag']), $config['fileActionSettings']);

    if (isset($multiple) && $multiple) {
        $config['maxFileCount'] = isset($maxFileCount) ? $maxFileCount : 5;
        $config['showPreview'] = true;
    }
    else {
        $config['overwriteInitial'] = true;
    }
    if (isset($maxFileSize) && $maxFileSize) {
        $config['maxFileSize'] = $maxFileSize / 1024;
    }

    if (isset($extensions)) {
        $config['allowedFileExtensions'] = array_values($extensions);
    }

    if ($required) {
        $config['required'] = $required;
        $config['msgFileRequired'] = '请选择一个文件上传！';
    }

    if ($placeholder) {
        $config['initialCaption'] = $placeholder;
    }

@endphp
@script
$(function() {
var config = @json($config);

@if(isset($previewUrl) && $previewUrl)
config.ajaxSettings.dataFilter = function(str, type) {
    if (type != 'json') {
        return str;
    }
    var jsonData = JSON.parse(str);
    if (jsonData.error !== undefined) {
        return JSON.stringify({error: jsonData.error});
    }

    var preview = [];
    var previewConfig = [];
    var previewThumbTags = [];

    if (typeof jsonData == 'object') {
        if (Object.prototype.toString.call(jsonData) !== '[object Array]') {
            jsonData = [jsonData];
        }

        for (i = 0; i < jsonData.length; i++) {
            preview.push('{{ url($previewUrl) }}'.replace('{guid}', jsonData[i].guid));
            previewConfig.push({
                key: jsonData[i].guid,
                caption: jsonData[i].name,
                filename: jsonData[i].name,
                filetype: jsonData[i].mimetype,
                size: jsonData[i].size,
                extra: {
                    '_method': 'DELETE'
                }
            });
            previewThumbTags.push({'##GUID##': jsonData[i].guid});
        }
    }

    return JSON.stringify({
        initialPreview: preview,
        initialPreviewConfig: previewConfig,
        initialPreviewThumbTags: previewThumbTags
    });
}
@endif

$('#{{ $id }}').fileinput(config);

@isset($deleteUrl)
var confirm_delete = false;
$('#{{ $id }}').on('filebeforedelete', function(event, key, data) {
    if (confirm_delete) {
        return false;
    }
    console.log(key);
    console.log(data);
    $('#modal_file_confirm').find('.modal-body').html('确定要删除文件吗？');
    $('#modal_file_confirm').off('hide.bs.modal').on('hide.bs.modal', function (e) {
        confirm_delete = false;
        $('#{{ $id }}').fileinput('_resetErrors');
        return true;
    });
    $('#modal_file_confirm_ok').off('click').on('click', function(e) {
        confirm_delete = true;
        $('#{{ $id }}').fileinput('getFrames', ' .kv-file-remove').click();
        $('#modal_file_confirm').modal('hide');
        return false;
    });
    $('#modal_file_confirm').modal('show');

    return {message: '确定删除文件?'};
});
@endisset

@isset($uploadUrl)
$('#{{ $id }}').closest('form').submit(function() {
    @if($config['uploadAsync'])
    if ($('#{{ $id }}').fileinput('getFileStack').length > 0) {
        $('#{{ $id }}').fileinput('_resetErrors');
        $('#{{ $id }}').fileinput('_showUploadError', '请点击“上传”文件');
        return false;
    }
    @endif

    return $('#{{ $id }}').fileinput('_isFileSelectionValid');
});
@endisset

});
@endscript