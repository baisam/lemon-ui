<?php
/**
 * Cell.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;


use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Cell
 *
 * @method bool         isVisible()
 *
 * @package BaiSam\UI\Grid
 */
class Cell implements Htmlable
{
    /**
     * @var Column
     */
    protected $column;

    protected $data = null;

    public function __construct(Column $column)
    {
        if ($column instanceof self) {
            $this->column = $column->column;
        }
        else {
            $this->column = $column;
        }
    }

    public function rawData()
    {
        return $this->data;
    }

    public function build(Row $row, Builder $builder)
    {
        $this->data = $this->column->render($row, $builder);
    }

    /**
     * 返回Html内容
     * @return bool|float|int|string
     */
    public function toHtml()
    {
        if (is_null($this->data)) {
            return '-';
        }

        if ($this->data instanceof Renderable) {
            return $this->data->render();
        }
        else if ($this->data instanceof Htmlable) {
            return $this->data->toHtml();
        }

        if (!is_scalar($this->data)) {
            return '<pre>'.var_export($this->data, true).'</pre>';
        }

        return e($this->data);
    }

    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * 继承Column的方法
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->column, $method), $arguments);
    }

}