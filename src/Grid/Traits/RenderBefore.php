<?php
/**
 * RenderBefore.php
 * BaiSam huixin
 *
 * Created by realeff on 2019/01/07.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Traits;

use Closure;

trait RenderBefore
{
    /**
     * @var callable
     */
    protected $before;

    /**
     * @param callable $before
     * @return $this
     */
    public function before(callable $before)
    {
        $this->before = $before;

        return $this;
    }

    /**
     * @param mixed $value
     * @param \BaiSam\UI\Grid\Row $row
     * @param callable $callback
     * @return mixed
     */
    protected function prepare($value, $row, callable $callback)
    {
        if (! isset($this->before) ) {
            return call_user_func($callback, $value, $row);
        }

        // 防止当前渲染动作向后传播
        $self = clone $this;
        call_user_func($this->before, $self, $value, $row);

        return call_user_func(Closure::bind($callback, $self), $value, $row);
    }
}