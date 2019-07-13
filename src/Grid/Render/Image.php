<?php
/**
 * Image.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Render;


use Closure;
use BaiSam\UI\Grid\Render as AbstractRender;

class Image extends AbstractRender
{

    protected $alt = '';

    protected $url = null;

    /**
     * Image width
     * @var string|int|float|null
     */
    protected $width;

    /**
     * Image height
     *
     * @var string|int|float|null
     */
    protected $height;

    protected $maxWidth;

    protected $maxHeight;


    /**
     * Image constructor.
     *
     * @param $name
     * @param $width
     * @param $height
     */
    public function __construct($name, $width = null, $height = null)
    {
        parent::__construct($name);

        if ($width || $height) {
            $this->dimensions($width, $height);
        }
    }

    /**
     * @param $width
     * @param null $height
     * @return $this
     */
    public function dimensions($width, $height = null)
    {
        if (is_numeric($width)) {
            $this->width = $width .'px';
        }
        else {
            $this->width = $width;
        }

        $height = isset($height) ? $height : $this->width;
        if (is_numeric($height)) {
            $this->height = $height .'px';
        }
        else {
            $this->height = $height;
        }

        return $this;
    }

    /**
     * @param $width
     * @param null $height
     * @return $this
     */
    public function maxDimensions($width, $height = null)
    {
        if (is_numeric($width)) {
            $this->maxWidth = $width .'px';
        }
        else {
            $this->maxWidth = $width;
        }
        $height = isset($height) ? $height : $this->maxWidth;
        if (is_numeric($height)) {
            $this->maxHeight = $height .'px';
        }
        else {
            $this->maxHeight = $height;
        }

        return $this;
    }

    public function alt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    public function render($value, $row, $builder)
    {
        if ($this->alt instanceof Closure) {
            $alt = call_user_func($this->alt, $row);
        }
        else {
            $alt = $this->alt;
        }
        if ($this->url instanceof Closure) {
            $url = call_user_func($this->url, $row);
        }
        else {
            $url = $this->url;
        }

        $data = [
            'src' => $value,
            'alt' => $alt,
            'url' => $url,
            'width' => $this->width,
            'height' => $this->height,
            'maxWidth' => $this->maxWidth,
            'maxHeight' => $this->maxHeight
        ];


        return view('ui::grid.image', $data);
    }

}