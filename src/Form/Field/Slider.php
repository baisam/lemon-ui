<?php
/**
 * SliderRange.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/20.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;

class Slider extends Field
{
    protected $min = 0;

    protected $max = 100;

    public function min($min = 0)
    {
        $this->min = $min;

        return $this;
    }

    public function max($max = 100)
    {
        $this->max = $max;

        return $this;
    }

    public function value()
    {
        $value = parent::value();

        return is_numeric($value) ? $value : null;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'min'           => $this->min,
            'max'           => $this->max
        ]);
    }
}