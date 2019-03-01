<?php
/**
 * Color.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/16.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;

class Color extends Field
{
    /**
     * @var string
     */
    protected $format = null;

    /**
     * Use `hex` format.
     *
     * @return $this
     */
    public function hex($format = 'hex')
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Use `rgb` format.
     *
     * @return $this
     */
    public function rgb($format = 'rgb')
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Use `rgba` format.
     *
     * @return $this
     */
    public function rgba($format = 'rgba')
    {
        $this->format = $format;

        return $this;
    }

    protected function variables()
    {
        $this->variables['format'] = $this->format;

        return parent::variables();
    }
}