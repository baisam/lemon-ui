<?php
/**
 * DateRange.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/18.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Suite;
use BaiSam\UI\Form\Traits\SuiteTrait;

class DateRange extends Date implements Suite
{
    use SuiteTrait;

    /**
     * @var Date
     */
    protected $start;

    /**
     * @var Date
     */
    protected $end;

    /**
     * DateRange constructor.
     *
     * @param string $column
     * @param string|null $label
     * @param array $config
     */
    public function __construct(string $column, string $label = null, array $config = [])
    {
        parent::__construct($column, $label, $config);

        $this->start = new Date('start');
        $this->end = new Date('end');
    }

    /**
     * @return array
     */
    public function getAccessories()
    {
        return ['start' => $this->start, 'end' => $this->end];
    }

    /**
     * Start date.
     *
     * @return Date
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * End date.
     *
     * @return Date
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        $this->start->format($format);
        $this->end->format($format);

        return parent::format($format);
    }

    /**
     * @return array
     */
    protected function variables()
    {
        $this->start->attribute($this->attributes);
        $this->end->attribute($this->attributes);

        $this->start->config($this->config);
        $this->end->config($this->config);

        $this->variables['start'] = $this->start;
        $this->variables['end']   = $this->end;

        $this->variables['startId'] = $this->start->formatId();
        $this->variables['endId']   = $this->end->formatId();

        return parent::variables();
    }
}