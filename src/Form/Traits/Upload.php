<?php
/**
 * Upload.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/21.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Traits;


use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

trait Upload
{
    /**
     * Upload name.
     *
     * @var string
     */
    private $_name;

    /**
     * Upload setting.
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
     * Max upload file of count.
     *
     * @var int
     */
    protected $maxFileCount = 2;

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
     * @var string
     */
    protected $uploadUrl = null;
    protected $deleteUrl = null;
    protected $previewUrl = null;
    protected $downloadUrl = null;

    /**
     * Initialize the upload.
     *
     * @param string $name
     * @throws LockTimeoutException
     */
    protected function initUpload($name = 'default')
    {
        // 从缓存中读取配置信息
        if (starts_with($name, '#UPLS')) {
            $setting = Cache::get($name);
            if (isset($setting)) {
                $name = $setting['name'];
            }
            else {
                throw new LockTimeoutException('Wait for upload timeout.');
            }
        }
        $this->_name = $name;

        $this->setting = $this->helper->getConfig('uploads.'. $name, []);
        // 加载默认配置
        $this->setting = array_merge($this->getDefaultSettings(), $this->setting);

        $this->initialSettings();

        // 重写当前配置
        if (isset($setting)) {
            if (isset($setting['directory'])) {
                $this->directory = $setting['directory'];
            }
            if (isset($setting['filename'])) {
                $this->filename = $setting['filename'];
            }
            if (isset($setting['extensions'])) {
                $this->extensions = $setting['extensions'];
            }
            if (isset($setting['size'])) {
                $this->maxFileSize = $setting['size'];
            }
            if (isset($setting['count'])) {
                $this->maxFileCount = $setting['count'];
            }
        }
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
        $this->maxFileCount  = $this->getSetting('maxFileCount', $this->maxFileCount);
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

    public function enableUpload()
    {
        $this->uploadUrl  = $this->getSetting('uploadUrl');

        return $this;
    }

    public function enableDelete()
    {
        $this->deleteUrl  = $this->getSetting('deleteUrl');

        return $this;
    }

    public function enablePreview()
    {
        $this->previewUrl = $this->getSetting('previewUrl');

        return $this;
    }

    public function enableDownload()
    {
        $this->downloadUrl= $this->getSetting('downloadUrl');

        return $this;
    }

    /**
     * Get storage driver.
     *
     * @return \Illuminate\Filesystem\FilesystemAdapter
     */
    protected function getStorageDriver()
    {
        if ($this->storage) {
            return $this->storage;
        }

        $disk = $this->getSetting('disk');

        if ($disk === 'default' || is_null($disk)) {
            $this->storage = Storage::drive();
        }
        else {
            if (!array_key_exists($disk, config('filesystems.disks'))) {
                $error = new MessageBag([
                    'title'   => 'Config error.',
                    'message' => "Disk [$disk] not configured, please add a disk config in `config/filesystems.php`.",
                ]);

                return session()->flash('error', $error);
            }

            $this->storage = Storage::drive($disk);
        }

        return $this->storage;
    }

    /**
     * Save the setting to the cache.
     *
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function buildUpload()
    {
        $setting = [
            'name'      => $this->_name,
            'directory' => $this->directory,
            'filename'  => $this->filename,
            'extensions'=> $this->extensions,
            'size'      => $this->maxFileSize,
            'count'     => $this->maxFileCount
        ];

        $key = '#UPLS'.md5(serialize($setting)). Carbon::now()->day;
        Cache::add($key, $setting, 1500);

        return $key;
    }

    /**
     * Save the file to file system.
     *
     * @param UploadedFile $file
     * @return bool|false|string
     */
    protected function saveUploadFile(UploadedFile $file)
    {
        //TODO 校验文件上传限制，文件类型，文件大小

        // 解析存储路径
        // {year} {month} {day} {dispersion}
        $path = $this->getSetting('path.format', '{dispersion}');

        // 根据文件的md5生成文件名
        $filename = md5_file($file->getRealPath());

        $dispersion = substr($filename, 0, 2);
        $filename = substr($filename, 2) .'.'. $file->extension();
        $now = Carbon::now();
        $replace_pairs = [
            '{year}' => $now->year,
            '{month}' => $now->month,
            '{day}' => $now->day,
            '{dispersion}' => $dispersion
        ];
        $path = trim($path, DIRECTORY_SEPARATOR);
        $path = $this->getRoot() .DIRECTORY_SEPARATOR. strtr($path, $replace_pairs);

        $filepath = $path .DIRECTORY_SEPARATOR. $filename;

        $driver = $this->getStorageDriver();
        if (!$driver->exists($filepath)) {
            // 存储文件
            $filepath = $driver->putFileAs($path, $file, $filename);
            if (false === $filepath) {
                return false;
            }
        }

        return $filepath;
    }

    /**
     *
     *
     * @param boolean $user
     * @return string
     */
    public function getRoot($user = true)
    {
        $domain = config('app.domain', 'default');
        $sid = substr(md5($domain . $this->_name), 8, 16);

        $root = $this->getSetting('path.root', 'upload');
        $replace_pairs = [
            '{domain}' => $domain,
            '{sid}' => $sid,
            '{directory}' => $this->directory
        ];

        $root = strtr($root, $replace_pairs);

        if ($user) {
            $uid = is_bool($user) ? Auth::id() : $user;
            $user = md5($root . $uid);
            $user = substr($user, 0, 2) .DIRECTORY_SEPARATOR. substr($user, 2, 2);
            $user .= DIRECTORY_SEPARATOR. $uid;
        }

        return $root .($user ? DIRECTORY_SEPARATOR .$user : '');
    }

    /**
     * Set or get the specified storage directory.
     *
     * @param string|null $directory
     * @return $this|string
     */
    public function directory($directory = null)
    {
        if (is_null($directory)) {
            return $this->directory;
        }

        $this->directory = $directory;

        return $this;
    }

    public function getPath($user = true)
    {
        $root = $this->getRoot($user);

        return $this->getStorageDriver()->path($root);
    }

    protected function extractFileMeta(UploadedFile $file)
    {
        $meta = [];
        $ext = $file->extension();

        //TODO 如果是图片文件，获取图片信息
        if (in_array($ext, $this->defaultTypes['image'])) {

            $meta['width'] = 0;
            $meta['height'] = 0;
            $meta['copyright'] = '';
            $meta['timestamp'] = '';
            $meta['camera'] = '';
            $meta['caption'] = '';
        }
        //TODO 如果是视频文件，获取视频信息（包括视频截图）
        if (in_array($ext, $this->defaultTypes['video'])) {

        }
        //TODO 如果是音频文件，获取音频信息（包括mp3封面图）
        if (in_array($ext, $this->defaultTypes['audio'])) {

        }

        return $meta;
    }

}