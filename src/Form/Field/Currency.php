<?php
/**
 * Currency.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/17.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


class Currency extends Text
{
    protected $symbol = '￥';

    public function symbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    protected function variables()
    {
        $this->variables['symbol'] = $this->symbol;

        return parent::variables();
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function toHtml()
    {
        $this->prepend($this->symbol);

        return parent::toHtml();
    }
}