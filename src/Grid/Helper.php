<?php
/**
 * Created by admin.
 * User: realeff
 * Date: 18-1-14
 * Time: 下午8:48
 */

namespace BaiSam\UI\Grid;


use BaiSam\UI\UIRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\Container;

/**
 * Class FormHelper
 *
 * 配置Grid类中相关渲染的的注册引用信息，及和Grid操作有关的公共方法
 *
 * @package BaiSam\UI\Grid
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
     * Configuration information for the grid.
     *
     * @var array
     */
    protected $config;

    /**
     * Available renders.
     *
     * @var array
     */
    protected $availableRenders = [];


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

        $this->registerBuiltinRenders();

        // Load config of extend field
        $this->registerExtendRenders();

        // Register resources.
        $this->registerGridResources();
    }

    /**
     * Register builtin renders.
     *
     * @return void
     */
    protected function registerBuiltinRenders()
    {
        foreach ([
                    'actions'       => \BaiSam\UI\Grid\Render\Actions::class,
                    'badge'         => \BaiSam\UI\Grid\Render\Badge::class,
                    'date'          => \BaiSam\UI\Grid\Render\Date::class,
                    'image'         => \BaiSam\UI\Grid\Render\Image::class,
                    'number'        => \BaiSam\UI\Grid\Render\Number::class,
                    'phone'         => \BaiSam\UI\Grid\Render\Phone::class,
                    'link'          => \BaiSam\UI\Grid\Render\Link::class,
                    'switcher'      => \BaiSam\UI\Grid\Render\Switcher::class,
                    'progress'      => \BaiSam\UI\Grid\Render\Progress::class,
                    'gender'        => \BaiSam\UI\Grid\Render\Gender::class,
                    'tags'          => \BaiSam\UI\Grid\Render\Tags::class
                 ] as $type => $class) {
            $this->extend($type, $class);
        }
    }

    /**
     * Register extend renders.
     *
     * @return void
     */
    protected function registerExtendRenders()
    {
        $extends = $this->getConfig('renders', []);

        foreach ($extends as $type => $class) {
            $this->extend($type, $class);
        }
    }

    /**
     * Extend the render for the grid.
     *
     * @param string $type
     * @param string $class
     *
     * @return void
     */
    public function extend($type, $class)
    {
        $this->availableRenders[$type] = $class;
    }

    /**
     * Find the class that specifies the render type.
     *
     * @param string $type
     *
     * @return bool|mixed
     */
    public function findRenderClass($type)
    {
        $class = Arr::get($this->availableRenders, $type);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * Register grid resources.
     */
    protected function registerGridResources()
    {
        foreach ([
            //TODO 必需引用的Grid资源
                 ] as $key => $resource) {
            $this->resource->registerIf($key, $resource);
        }
    }

    /**
     * Get resource for the grid.
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

}