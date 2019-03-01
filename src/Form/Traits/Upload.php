<?php
/**
 * Upload.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/21.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Traits;


use Illuminate\Support\Facades\Storage;

trait Upload
{
    /**
     * Uplaod setting.
     *
     * @var array
     */
    protected $setting;

    /**
     * Storage instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * Store directory.
     *
     * @var string
     */
    protected $directory;

    /**
     * Store name of the file.
     *
     * @var string
     */
    protected $filename;

    /**
     * If use unique name to store upload file.
     *
     * @var bool
     */
    protected $useUniqueName = false;

    /**
     * Allows the upload file extensions.
     *
     * @var array
     */
    protected $extensions;

    /**
     * Max upload file of size.
     *
     * @var int
     */
    protected $maxFileSize = 10240;

    /**
     * Default file types.
     *
     * @var array
     */
    protected $defaultTypes = [
        'image' => ['gif', 'png', 'jpg', 'jpeg'],
        'video' => ['ogg', 'mp4', 'mpeg', 'mov', 'webm', '3gp'],
        'audio' => ['ogg', 'mp3', 'mpeg', 'wav']
    ];

    /**
     * Initialize the upload.
     *
     * @param string $name
     */
    protected function initUpload($name = 'default')
    {
        $this->setting = $this->helper->getConfig('uploads.'. $name, []);

        if (empty($this->setting) && $name !== 'default') {
            $this->setting = $this->getDefaultSettings();
        }

        $this->initialSettings();
    }

    /**
     * Get the default upload Settings.
     *
     * @return array
     */
    protected function getDefaultSettings()
    {
        return $this->helper->getConfig('uploads.default', []);
    }

    /**
     * Initialize the upload settings.
     */
    private function initialSettings()
    {
        $this->maxFileSize   = $this->getSetting('maxFileSize', $this->maxFileSize);
        $this->useUniqueName = $this->getSetting('uniqueName', false);

        $extensions = array_wrap($this->getSetting('allowExtensions', []));
        $fileTypes = array_wrap($this->getSetting('allowFileTypes', []));

        foreach ($fileTypes as $fileType) {
            $extensions = array_merge($extensions, array_get($this->defaultTypes, $fileType, []));
        }

        $this->extensions = array_unique($extensions);
    }

    /**
     * Get upload setting.
     *
     * @param string|null $name
     * @param mixed $default
     * @return array|mixed
     */
    protected function getSetting($name = null, $default = null)
    {
        if (is_null($name)) {
            return $this->setting;
        }

        return array_get($this->setting, $name, $default);
    }

    /**
     * Set extensions for upload file.
     *
     * @param array $extensions
     * @return $this
     */
    public function extensions(array $extensions)
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * Get storage driver.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getStorageDriver()
    {
        if ($this->storage) {
            return $this->storage;
        }

        $disk = $this->getSetting('disk');

        if ($disk === 'default' || is_null($disk)) {
            $this->storage = Storage::disk();
        }
        else {
            if (!array_key_exists($disk, config('filesystems.disks'))) {
                $error = new MessageBag([
                    'title'   => 'Config error.',
                    'message' => "Disk [$disk] not configured, please add a disk config in `config/filesystems.php`.",
                ]);

                return session()->flash('error', $error);
            }

            $this->storage = Storage::disk($disk);
        }

        return $this->storage;
    }

    public function setDirectory($dir)
    {
        return $this;
    }

    public function directory()
    {
        // 获取文件绝对目录

        return $this->directory;
    }

    public function path()
    {
        // 获取文件相对存储器根目录的路径
    }

    public function setFileName($filename)
    {
        //TODO 仅对单个文件有效?

        return $this;
    }

    public function basename()
    {
        // 获取文件名
    }

    public function filename()
    {
        // 获取完整文件名信息
    }

    /**
     * 读取并初始化上传配置
     * 初始化客户端上传配置并准备输出
     * 存储盘＼存储路径及格式＼上传类型＼上传大小＼文件名格式
     * 启用远程上传＼启用远程删除
     * 校验文件上传限制
     * 存储并转换上传文件
     */

    // 检查上传目录
    // 检查文件扩展名
    // 设置或获取上传文件路径
    // 生成统一命名文件
    // 上传文件
    // 删除文件
}