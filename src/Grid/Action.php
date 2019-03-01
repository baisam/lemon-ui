<?php
/**
 * Action.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid;


interface Action
{
    /**
     * @return boolean
     */
    public function needSelectRow();

    /**
     * 设置内容可见性
     *
     * @param boolean|callable $visible
     * @return $this
     */
    public function visible($visible);

    /**
     * @param mixed ...$params
     * @return mixed
     */
    public function render(...$params);

}