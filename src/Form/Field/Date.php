<?php
/**
 * Date.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/17.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use Illuminate\Support\Carbon;

class Date extends Field
{
    protected $format = 'Y-m-d';

    protected $minDate;

    protected $maxDate;

    /**
     * Set output format for the date.
     *
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function minDate($date)
    {
        $this->minDate = $date;

        return $this;
    }

    public function maxDate($date)
    {
        $this->maxDate = $date;

        return $this;
    }

    public function value()
    {
        $value = $this->value ?: $this->original();

        $value = is_scalar($value) || $value instanceof Carbon ? $value : null;

        return $value ? Carbon::make($value)->format($this->format) : null;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'format'        => $this->format,
            'minDate'       => $this->minDate,
            'maxDate'       => $this->maxDate
        ]);
    }

}