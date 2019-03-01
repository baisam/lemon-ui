<?php
/**
 * Date.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Render;


use BaiSam\Contracts\Format;
use BaiSam\UI\Grid\Render as AbstractRender;
use Illuminate\Support\Carbon;

class Date extends AbstractRender implements Format
{
    protected $format = 'Y-m-d';


    /**
     * Date constructor.
     *
     * @param $name
     * @param string|null $format
     */
    public function __construct($name, string $format = null)
    {
        parent::__construct($name);

        if (isset($format)) {
            $this->format($format);
        }
    }

    /**
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function render($value, $row, $builder)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->format($this->format);
        }

        return (new Carbon($value))->format($this->format);
    }

}