<?php
/**
 * SubmitAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


class SubmitAction extends ButtonAction
{
    protected $selectRow = true;

    protected $type = 'submit';

    public function disableSelectRow()
    {
        $this->selectRow = false;

        return $this;
    }

    public function needSelectRow()
    {
        return $this->selectRow;
    }

    protected function formatAttributes()
    {
        if ($this->selectRow) {
            $this->attribute('select-rows', true);
        }

        return parent::formatAttributes();
    }
}