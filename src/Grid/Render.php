<?php
/**
 * Render.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;

/**
 * Class Render
 *
 * @method $this           setKey($name)
 * @method $this           hidden($hidden = true)
 * @method $this           editable($key = null)
 * @method $this           sortable($key = null)
 * @method $this           weight($weight)
 * @method $this           format($format)
 * @method $this           width($width)
 *
 * @package BaiSam\UI\Grid
 */
class Render
{
    /**
     * @var \BaiSam\UI\Grid\Helper
     */
    protected $helper;

    /**
     * Render name.
     *
     * @var string
     */
    protected $name;

    /**
     * Render type.
     *
     * @var string
     */
    protected $type;

    /**
     * Render constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->type = strtolower(class_basename($this));
    }

    /**
     * Get the type for the render.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $helper
     */
    public function setHelper($helper)
    {
        $this->helper = $helper;
    }

    /**
     * 获取渲染样式配置值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getStyle($key, $default = null)
    {
        $styles = $this->helper->getConfig('styles.'. $this->type, []);
        if (isset($styles[0]) && is_string($styles[0])) {
            $styles = array_merge($this->helper->getConfig('styles.'. array_pull($styles, 0), []), $styles);
        }

        return array_get($styles, strtolower($key), $default);
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @param mixed $value;
     * @param Row $row
     * @param Builder $builder
     * @return string
     */
    public function render($value, $row, $builder)
    {
        return $value;
    }
}