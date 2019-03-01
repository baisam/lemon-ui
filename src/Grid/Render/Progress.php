<?php
/**
 * Progress.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */

namespace BaiSam\UI\Grid\Render;


use Closure;
use BaiSam\UI\Grid\Traits\RenderBefore;
use BaiSam\UI\Grid\Render as AbstractRender;
use BaiSam\UI\UIRepository;

class Progress extends AbstractRender
{
    use RenderBefore;

    /**
     * @var int
     */
    protected $max;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var boolean
     */
    protected $vertical = false;

    /**
     * @var boolean
     */
    protected $striped = false;

    /**
     * @var boolean
     */
    protected $active = false;

    /**
     * @var string
     */
    protected $size;

    /**
     * @var string
     */
    protected $color;

    /**
     * Progress constructor.
     *
     * @param string $name
     * @param int $max
     */
    public function __construct(string $name, $max = 100)
    {
        parent::__construct($name);

        $this->max = $max;
    }

    /**
     * Set the maximum value of the progress.
     *
     * @param int $max
     * @return $this
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Set vertical for the progress.
     *
     * @param boolean $vertical
     * @return $this
     */
    public function vertical($vertical = true)
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * @param boolean $striped
     * @return $this
     */
    public function striped($striped = true)
    {
        $this->striped = $striped;

        return $this;
    }

    /**
     * @param boolean $active
     * @return $this
     */
    public function active($active = true)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Set size for the progress.
     *
     * @param string $size
     *
     * @return $this
     */
    public function size($size = UIRepository::STYLE_DEFAULT)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Set color for the progress.
     *
     * @param string $color
     *
     * @return $this
     */
    public function color($color = UIRepository::STYLE_DEFAULT)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string|\Closure $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;

        return $this;
    }

    protected function formatLabel($value, $row)
    {
        if (isset($this->label)) {
            if ($this->label instanceof Closure) {
                return call_user_func($this->label, $value, $row);
            }

            return $this->label;
        }

        return null;
    }

    public function render($value, $row, $builder)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        // 调用模板输出 ui::grid.progress
        $data = $this->prepare($value, $row, function ($value, $row) use ($builder) {
            // 百分比保留3位小数
            $percent = floor(min($value / $this->max, 1) * 100000) / 1000;
            // 进度标签
            $label = $this->formatLabel($value, $row);
            if (isset($label) && is_string($label)) {
                $label = str_replace('{0}', $percent, $label);
                $label = str_replace('{1}', $this->max, $label);
            }

            return [
                //'id'    => $builder->getId() .'_row_'. $row->getMajorKey() .'_'. $this->name,
                'name'      => $this->name,
                'value'     => $value,
                'max'       => $this->max,
                'percent'   => $percent,
                'label'     => $label,
                'size'      => $this->size ? $this->getStyle('size.'. $this->size) : null,
                'color'     => $this->color ? $this->getStyle('color.'. $this->color) : null,
                'vertical'  => $this->vertical,
                'striped'   => $this->striped,
                'active'    => $this->active
            ];
        });

        return view('ui::grid.progress', $data);
    }

}