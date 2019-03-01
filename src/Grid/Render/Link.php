<?php
/**
 * Link.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Render;


use Closure;
use BaiSam\UI\Grid\Render as AbstractRender;
use Illuminate\Support\HtmlString;

class Link extends AbstractRender
{
    protected $url = '';

    protected $target = '';

    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    public function render($value, $row, $builder)
    {
        $target = $this->target ? '' : '';
        if ($this->url instanceof Closure) {
            $url = call_user_func($this->url, $row);
        }
        else {
            $url = $this->url;
        }

        $html = <<<EOT
<a href="{$url}" {$target}>{$value}</a>
EOT;

        return new HtmlString($html);
    }

}