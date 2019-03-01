<?php
/**
 * File.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use BaiSam\UI\Form\Traits\Upload;

class File extends Field
{
    use Upload;

    public function __construct(string $column, string $label = null, $config = null)
    {
        if (is_string($config)) {
            $config = ['upload' => $config];
        }

        parent::__construct($column, $label, $config);

        // 初始化存储设备及目录
        if (is_array($config) && isset($config['upload'])) {
            $this->initUpload($config['upload']);
        }
        else {
            $this->initUpload('file');
        }
    }

    //TODO 离线上传配置（包括上传地址等信息）

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'maxFileSize'       => $this->maxFileSize,
            'extensions'        => $this->extensions
        ]);
    }
}