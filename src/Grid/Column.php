<?php
/**
 * Column.php
 * BaiSam admin
 *
 * Created by realeff on 2018/06/07.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;

use Closure;
use BadMethodCallException;
use BaiSam\Contracts\Sortable;
use BaiSam\Contracts\Format;
use Illuminate\Support\HtmlString;

/**
 * Class Column
 *
 * @method Render\Actions           actions(Closure $callback)
 * @method Render\Badge             badge($values = [])
 * @method Render\Date              date($format = null)
 * @method Render\Image             image($width = null, $height = null)
 * @method Render\Link              link()
 * @method Render\Number            number()
 * @method Render\Phone             phone()
 * @method Render\Progress          progress($max = 100)
 * @method Render\Switcher          switcher()
 * @method Render\Gender            gender([] $_map = null)
 * @method Render\Tags              tags()
 *
 * @package BaiSam\UI\Grid
 */
class Column implements Sortable
{
    /**
     * 升序
     */
    const SORT_ASC = 'ASC';
    /**
     * 降序
     */
    const SORT_DESC = 'DESC';

    /**
     * @var \BaiSam\UI\Grid\Helper
     */
    protected $helper;

    /**
     * 列数据集的键名
     * @var string
     */
    protected $key;

    /**
     * 列名
     * @var string
     */
    protected $name;

    /**
     * 列的标题
     * @var string
     */
    protected $title;

    /**
     * 是否隐藏的列
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * 是否可编辑的列
     *
     * @var bool
     */
    protected $editable = false;

    /**
     * 是否可排序的列
     *
     * @var bool
     */
    protected $sortable = false;

    /**
     * 列的权重
     * @var int
     */
    protected $weight = 0;

    /**
     * 列的输出宽度
     * @var int
     */
    protected $width = null;

    /**
     * @var string|Closure
     */
    protected $format;

    /**
     * 内容渲染器
     * @var mixed
     */
    protected $render;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param string|null $title
     */
    public function __construct($name, $title = null)
    {
        $this->name = $name;
        $this->title = $title ?: title_case($name);

        $this->key = snake_case($name);

        // make form helper
        $this->helper = app('grid.helper');
    }

    /**
     * 获取列渲染类型
     *
     * @return string
     */
    public function getType()
    {
        if (isset($this->render)) {
            return $this->render->getType();
        }

        return 'none';
    }

    /**
     * 获取列键名
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 获取列标题
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 设置字段名
     * @param string $name
     * @return $this
     */
    public function setKey($name)
    {
        $this->key = $name;

        return $this;
    }

    /**
     * 设置为隐藏列
     * @param bool $hidden
     * @return $this
     */
    public function hidden($hidden = true)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * 是否可见列
     * @return bool
     */
    public function isVisible()
    {
        return !$this->hidden;
    }

    public function editable($key = null)
    {
        //TODO 设置编辑字段，默认为Key
        $this->editable = isset($key) ? $key : $this->key;

        return $this;
    }

    /**
     * 设置列排序
     *
     * @param string|null $name
     * @param string|null $sorting
     * @return $this
     */
    public function sortable($name = null, $sorting = null)
    {
        $name = isset($name) ? $name : $this->key;
        $sorting = isset($sorting) ? strtoupper($sorting) : null;

        if (isset($sorting) && in_array($sorting, [self::SORT_ASC, self::SORT_DESC])) {
            // 设置排序字段，KEY,ASC
            $this->sortable = [$name, $sorting];
        }
        else {
            $this->sortable = $name;
        }

        return $this;
    }

    /**
     * 获取列排序
     *
     * @return mixed
     */
    public function sorting()
    {
        if ($this->sortable) {
            $request = app('request');
            list($name) = (array)$this->sortable;

            if ($request->has('order') && $request->get('order') == $name) {
                $sorting = $request->has('orderby') ? strtoupper($request->get('orderby')) : null;
                if ($sorting !== self::SORT_ASC && $sorting !== self::SORT_DESC) {
                    $sorting = self::SORT_ASC;
                }
                // 排序字段，KEY,ASC
                return [$name, $sorting];
            }
        }


        return $this->sortable;
    }

    /**
     * 设置或获取权重
     * @param int|null $weight
     * @return $this
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
     * 格式化内容
     *
     * @param string|callable $format
     * @return $this
     */
    public function format($format)
    {
        // 根据输出类型决定format输入格式，一般允许是字符串、回调函数、实现Format接口的实例
        $this->format = $format;

        return $this;
    }

    /**
     * 设置列显示宽度
     * @param int|string $width
     * @return $this
     */
    public function width($width = null)
    {
        if (is_null($width)) {
            return $this->width;
        }

        $this->width = $width;

        return $this;
    }

    public function render(Row $row, Builder $builder)
    {
        $value = $row->{$this->key};

        if (isset($this->format)) {
            if (is_callable($this->format)) {
                $value = call_user_func($this->format, $value, $row, $builder);
            }
            else if ($this->render instanceof Format) {
                $this->render->format($this->format);
            }
        }

        if (isset($this->render)) {
            if ($this->render instanceof Render) {
                $value = $this->render->render($value, $row, $builder);
            }
            else {
                $value = $this->render;
            }
        }

        if ($this->hidden) {
            $value = e($value);
            $html = <<<EOT
<input id="{$builder->getId()}_row_{$row->getMajorKey()}_{$this->key}" type="hidden" name="{$builder->getId()}_row[{$row->getMajorKey()}][{$this->key}]" value="{$value}" />
EOT;
            return new HtmlString($html);
        }

        if ($this->editable) {
            //TODO 列内容可编辑
            $this->helper->getResource()->requireResource('grid.editable');
        }

        return $value;
    }

    /**
     * 引用渲染器
     * @param string $method
     * @param array $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        // 如果渲染器不存在,则检查是否初始化渲染器
        if ( !isset($this->render) && ($class = $this->helper->findRenderClass($method)) ) {
            array_unshift($arguments, $this->name);
            // 实例化新渲染器
            $this->render = new $class(...$arguments);
            if ($this->render instanceof Render) {
                $this->render->setHelper($this->helper);
            }

            return $this;
        }

        if ($this->render instanceof Render && $this->render->getType() == $method) {
            return $this;
        }
        // 检查渲染器方法是否存在,如果不存在则抛出异常操作
        else if (isset($this->render) && method_exists($this->render, $method)) {
            $return = call_user_func_array(array($this->render, $method), $arguments);
            if ($return !== $this->render) {
                return $return;
            }
        }
        else {
            throw new BadMethodCallException($method .' does not exist');
        }

        return $this;
    }

}