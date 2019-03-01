<?php
/**
 * Html.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/20.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;

class Html extends Field
{
    protected $ignored = true;

    protected $html = null;

    /**
     * @param string|Htmlable $html
     *
     * @return $this
     */
    public function html($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value ?: $this->original();
    }

    /**
     * @return mixed|string|null
     */
    public function toHtml()
    {
        $this->rendered = true;

        $html = isset($this->html) ? $this->html : $this->value();

        if ($html instanceof Renderable) {
            return $html->render();
        }
        else if ($html instanceof Htmlable) {
            return $html->toHtml();
        }

        return $html;
    }
}