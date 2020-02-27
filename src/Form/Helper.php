<?php
/**
 * Created by admin.
 * User: realeff
 * Date: 18-1-14
 * Time: 下午8:48
 */

namespace BaiSam\UI\Form;


use BaiSam\UI\UIRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\Container;

/**
 * Class FormHelper
 *
 * 配置Form类中相关字段的的注册引用信息，及和表单操作有关的公共方法
 *
 * @package BaiSam\UI\Form
 */
class Helper
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @var UIRepository
     */
    protected $resource;

    /**
     * Configuration information for the form.
     *
     * @var array
     */
    protected $config;

    /**
     * Available fields.
     *
     * @var array
     */
    protected $availableFields = [];


    /**
     * Create a new connection factory instance.
     *
     * @param \Illuminate\Contracts\Container\Container  $container
     * @param \BaiSam\UI\UIRepository $resource
     * @param array $config
     *
     * @return void
     */
    public function __construct(Container $container, UIRepository $resource, array $config = [])
    {
        $this->container = $container;
        $this->resource  = $resource;
        $this->config    = $config;

        $this->registerBuiltinFields();

        // Load config of extend field
        $this->registerExtendFields();

        // Register resources.
        $this->registerFormResources();
    }

    /**
     * Register builtin fields.
     *
     * @return void
     */
    protected function registerBuiltinFields()
    {
        foreach ([
                     'button'        => \BaiSam\UI\Form\Field\Button::class,
                     'submit'        => \BaiSam\UI\Form\Field\Submit::class,
                     'reset'         => \BaiSam\UI\Form\Field\Reset::class,
                     'label'         => \BaiSam\UI\Form\Field\Label::class,
                     'captcha'       => \BaiSam\UI\Form\Field\Captcha::class,
                     'checkbox'      => \BaiSam\UI\Form\Field\Checkbox::class,
                     'color'         => \BaiSam\UI\Form\Field\Color::class,
                     'currency'      => \BaiSam\UI\Form\Field\Currency::class,
                     'date'          => \BaiSam\UI\Form\Field\Date::class,
                     'daterange'     => \BaiSam\UI\Form\Field\DateRange::class,
                     'decimal'       => \BaiSam\UI\Form\Field\Decimal::class,
                     'editor'        => \BaiSam\UI\Form\Field\Editor::class,
                     'email'         => \BaiSam\UI\Form\Field\Email::class,
                     'file'          => \BaiSam\UI\Form\Field\File::class,
                     'html'          => \BaiSam\UI\Form\Field\Html::class,
                     'image'         => \BaiSam\UI\Form\Field\Image::class,
                     'ip'            => \BaiSam\UI\Form\Field\Ip::class,
                     'number'        => \BaiSam\UI\Form\Field\Number::class,
                     'password'      => \BaiSam\UI\Form\Field\Password::class,
                     'phone'         => \BaiSam\UI\Form\Field\Phone::class,
                     'radio'         => \BaiSam\UI\Form\Field\Radio::class,
                     'select'        => \BaiSam\UI\Form\Field\Select::class,
                     'slider'        => \BaiSam\UI\Form\Field\Slider::class,
                     'switcher'      => \BaiSam\UI\Form\Field\Switcher::class,
                     'tags'          => \BaiSam\UI\Form\Field\Tags::class,
                     'text'          => \BaiSam\UI\Form\Field\Text::class,
                     'textarea'      => \BaiSam\UI\Form\Field\Textarea::class,
                     'url'           => \BaiSam\UI\Form\Field\Url::class,
                     'hidden'        => \BaiSam\UI\Form\Field\Hidden::class,
                     'datasheet'     => \BaiSam\UI\Form\Field\DataSheet::class
                 ] as $type => $class) {
            $this->extend($type, $class);
        }
    }

    /**
     * Register extend fields.
     *
     * @return void
     */
    protected function registerExtendFields()
    {
        $extends = $this->getConfig('fields', []);

        foreach ($extends as $type => $class) {
            $this->extend($type, $class);
        }
    }

    /**
     * Extend the field type for the form.
     *
     * @param string $type
     * @param string $class
     *
     * @return void
     */
    public function extend($type, $class)
    {
        $this->availableFields[$type] = $class;
    }

    /**
     * Find the class that specifies the field type.
     *
     * @param string $type
     *
     * @return bool|mixed
     */
    public function findFieldClass($type)
    {
        $class = Arr::get($this->availableFields, $type);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Register form resources.
     */
    protected function registerFormResources()
    {
        foreach ([
            //TODO 必需引用的表单资源
                 ] as $key => $resource) {
            $this->resource->registerIf($key, $resource);
        }
    }

    /**
     * Get resource for the form.
     *
     * @return \BaiSam\UI\UIRepository
     */
    public function getResource()
    {
        return $this->resource->getInstance();
    }

    /**
     * Get config for the form.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        return Arr::get($this->config, strtolower($key), $default);
    }

    //TODO 合并表单字段属性

}