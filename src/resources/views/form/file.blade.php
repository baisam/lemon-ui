<input id="{{ $id }}" type="file" class="{{ $class }}" name="{{ $name }}" {!! $attributes !!} />
@php
    $config = array_merge($config, [
        'language' => 'zh',
        'showPreview' => false,
        'showCaption' => true,
        'showRemove'  => true,
        'showUpload'  => false,
        'resizeImage' => false,
        'purifyHtml' => true,
        'maxFilePreviewSize' => 10240, // 10M
        'allowedPreviewTypes' => ['image', 'html', 'text', 'video', 'audio', 'flash'],
        'browseLabel' => '浏览',
        'removeLabel' => '删除',
        'uploadLabel' => '上传',
        'msgFilesTooMany' => '选择上传的文件数量({n}) 超过允许的最大数值{m}！'
    ]);
    $config['mainClass'] = 'input-group-sm';
    $config['overwriteInitial'] = true;
    $config['ajaxSettings'] = [
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
                'X-CSRF-TOKEN' => csrf_token()
            ]
        ];

    if (isset($multiple) && $multiple) {
        $config['maxFileCount'] = isset($maxFileCount) ? $maxFileCount : 5;
    }
    if (isset($maxFileSize)) {
        $config['maxFileSize'] = $maxFileSize;
    }

    if (isset($extensions)) {
        $config['allowedFileExtensions'] = array_values($extensions);
    }

    if ($required) {
        $config['required'] = $required;
        $config['msgFileRequired'] = '请选择一个文件上传！';
    }

    if ($placeholder) {
        $config['msgPlaceholder'] = $placeholder;
    }
@endphp
@script
$(function() {
  $('#{{ $id }}').fileinput(@json($config));
});
@endscript