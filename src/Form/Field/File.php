<?php
/**
 * File.php
 * User: realeff
 * Date: 17-11-14
 */

namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use BaiSam\UI\Form\Traits\Upload;
use Illuminate\Http\UploadedFile;

class File extends Field
{
    use Upload;

    /**
     * @var boolean
     */
    protected $multiple = false;

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

    /**
     * @param boolean $multiple
     * @return $this
     */
    public function multiple($multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    //TODO 离线上传配置（包括上传地址等信息）

    protected function formatAttributes()
    {
        if ($this->multiple) {
            $this->attribute('multiple', true);
        }

        return parent::formatAttributes();
    }


    protected function variables()
    {
        $max_filesize = UploadedFile::getMaxFilesize();
        $max_filesize = min($max_filesize, $this->maxFileSize);

        return array_merge(parent::variables(), [
            'multiple'          => $this->multiple,
            'uploadUrl'         => $this->uploadUrl,
            'uploadKey'         => $this->buildUpload(),
            'maxFileSize'       => $max_filesize,
            'maxFileCount'      => $this->maxFileCount,
            'extensions'        => $this->extensions,
            'deleteUrl'         => $this->deleteUrl,
            'previewUrl'        => $this->previewUrl,
            'downloadUrl'       => $this->downloadUrl
        ]);
    }
}