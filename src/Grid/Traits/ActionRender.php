<?php
/**
 * ActionRender.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/31.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Traits;


use Exception;
use BaiSam\UI\Grid\Row;
use BaiSam\UI\Grid\Builder;

trait ActionRender
{
    protected $params;

    /**
     * 内容可见性
     *
     * @var bool
     */
    protected $visibility = true;


    protected function formatId()
    {
        if (isset($this->params) && count($this->params) == 3
            && $this->params[1] instanceof Row
            && $this->params[2] instanceof Builder) {

            return $this->params[2]->getId() .'_row_'. $this->params[0] .'_'. parent::formatId();
        }

        return parent::formatId();
    }

    /**
     * @param boolean|callable $visible
     * @return $this
     */
    public function visible($visible)
    {
        $this->visibility = $visible;

        return $this;
    }

    public function render(...$params)
    {
        try {
            // 检查内容是否可见
            if (is_callable($this->visibility) &&
                ! call_user_func_array($this->visibility, $params)) {
                return null;
            }
            else if (! $this->visibility) {
                return null;
            }

            $this->params = $params;

            // clone防止内存死锁，导致内存异出
            return tap(clone $this, function () {
                $this->params = null;
            });
        }
        catch (Exception $e) {
            $this->params = null;

            throw $e;
        }
    }

}