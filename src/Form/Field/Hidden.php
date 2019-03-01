<?php
/**
 * Hidden.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/08.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;

class Hidden extends Field
{

    /**
     * @return string
     */
    public function toHtml()
    {
        $this->rendered = true;

        $id = e($this->formatId());
        $name = e($this->formatName());
        $value = e($this->value());

        return <<<EOT
<input id="{$id}" type="hidden" name="{$name}" value="{$value}" />
EOT;
    }

}