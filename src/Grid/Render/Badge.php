<?php
/**
 * Badge.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Render;


use Closure;
use BaiSam\UI\Grid\Traits\RenderBefore;
use BaiSam\UI\Grid\Render as AbstractRender;
use Illuminate\Support\Arr;

class Badge extends AbstractRender
{
    use RenderBefore;

    /**
     * @var string
     */
    protected $shape = 'badge';

    /**
     * @var string
     */
    protected $color;

    protected $values;

    /**
     * Badge constructor.
     *
     * @param string $name
     * @param array|null $values
     */
    public function __construct(string $name, array $values = null)
    {
        parent::__construct($name);

        if (isset($values)) {
            $this->values = $values;
        }
    }

    /**
     * Set shape for the badge.
     *
     * @param string $shape
     * @return $this
     */
    public function shape($shape)
    {
        $this->shape = $shape;

        return $this;
    }

    /**
     * Set color.
     *
     * @param string|array|Closure $color
     * @return $this
     */
    public function color($color)
    {
        $this->color = $color;

        return $this;
    }

    protected function buildValue($value)
    {
        if (isset($this->values) && Arr::has($this->values, $value)) {
            $value = Arr::get($this->values, $value);
        }

        return $value;
    }

    protected function buildColor($value)
    {
        if (isset($this->color)) {
            if (is_array($this->color)) {
                return Arr::has($this->color, $value) ? Arr::get($this->color, $value) : null;
            }
            if (is_callable($this->color)) {
                return call_user_func($this->color, $value);
            }

            return $this->color;
        }

        return null;
    }

    public function render($value, $row, $builder)
    {
        // 调用模板输出 ui::grid.badge
        $data = $this->prepare($value, $row, function ($value, $row) use ($builder) {
            return [
                //'id'    => $builder->getId() .'_row_'. $row->getMajorKey() .'_'. $this->name,
                'value' => $this->buildValue($value),
                'shape' => $this->shape,
                'color' => $this->buildColor($value)
            ];
        });

        return view('ui::grid.badge', $data);
    }

}