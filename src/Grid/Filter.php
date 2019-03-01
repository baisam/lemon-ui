<?php
/**
 * Filter.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/02.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;


use BaiSam\UI\Form\Field;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Request;

/**
 * Class Filter
 *
 * @method Field\Checkbox       checkbox($config = null)
 * @method Field\Date           date($config = null)
 * @method Field\DateRange      daterange($config = null)
 * @method Field\Decimal        decimal($config = null)
 * @method Field\Number         number($config = null)
 * @method Field\Phone          phone($config = null)
 * @method Field\Radio          radio($config = null)
 * @method Field\Select         select($config = null)
 * @method Field\Switcher       switcher($config = null)
 * @method Field\Text           text($config = null)
 *
 * @package BaiSam\UI\Grid
 */
class Filter implements Htmlable
{
    /**
     * @var \BaiSam\UI\Form\Helper
     */
    protected $helper;

    /**
     * 过滤名
     * @var string
     */
    protected $name;

    /**
     * 过滤的标题
     * @var string
     */
    protected $label;

    /**
     * 显示的权重
     * @var int
     */
    protected $weight = 0;

    /**
     * 输出宽度
     * @var int
     */
    protected $width = 3;

    /**
     * 支持的字段类型
     * @var array
     */
    protected $supportFieldTypes = ['checkbox', 'date', 'daterange', 'decimal', 'number', 'phone', 'radio', 'select', 'switcher', 'text'];

    /**
     * 过滤字段
     * @var Field
     */
    protected $field;

    /**
     * Filter constructor.
     *
     * @param $name
     * @param null $label
     */
    public function __construct($name, $label = null)
    {
        $this->name = $name;
        $this->label = isset($label) ? title_case($label) : '';

        // make form helper
        $this->helper = app('form.helper');
    }

    /**
     * 获取过滤名
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 获取过滤标题
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * 设置或获取权重
     * @param int|null $weight
     * @return $this|int
     */
    public function weight($weight = null)
    {
        if (is_null($weight)) {
            return $this->weight;
        }

        $this->weight = $weight;

        return $this;
    }

    /**
     * 设置或获取显示宽度
     * @param int|null $width
     * @return $this|int
     */
    public function width($width = null)
    {
        if (is_null($width)) {
            return $this->width;
        }

        $this->width = $width;

        return $this;
    }

    /**
     * @return Field
     */
    public function field()
    {
        if ( ! isset($this->field) ) {
            $this->text();
        }

        return $this->field;
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        $this->field()->setValue(Request::get($this->getName()));

        return $this->field()->toHtml();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * 引用字段
     * @param string $method
     * @param array $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if ( !isset($this->field) && in_array($method, $this->supportFieldTypes)
            && ($class = $this->helper->findFieldClass($method))) {
            // 支持函数回调
            $callback = null;
            if (isset($arguments[0]) && is_callable($arguments[0])) {
                $callback = array_shift($arguments);
            }

            // 初始化字段名
            switch ($method) {
                case 'daterange':
                    if ($this->width === 3) {
                        $this->width = 4;
                    }
                    break;
                default:
                    if ($this->width === 3) {
                        $this->width = 2;
                    }
                    break;
            }

            array_unshift($arguments, $this->getName(), $this->getLabel());

            // 实例化字段
            $this->field = new $class(...$arguments);

            if (isset($callback)) {
                call_user_func($callback, $this->field);
            }

            return $this;
        }

        if (isset($this->field) && $this->field->getType() == $method) {
            return $this;
        }
        // 检查字段方法是否存在,如果不存在则抛出异常操作
        else if (isset($this->field) && method_exists($this->field, $method)) {
            $return = call_user_func_array(array($this->field, $method), $arguments);
            if ($return !== $this->field) {
                return $return;
            }
        }
        else {
            throw new BadMethodCallException($method .' does not exist');
        }

        return $this;
    }
}