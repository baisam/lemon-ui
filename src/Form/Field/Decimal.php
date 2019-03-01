<?php
/**
 * Decimal.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/20.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


class Decimal extends Text
{

    protected $digits = 2;

    public function digits($digits = 2)
    {
        $this->digits = $digits;

        return $this;
    }

    protected function variables()
    {
        $this->variables['digits'] = $this->digits;

        return parent::variables();
    }
}