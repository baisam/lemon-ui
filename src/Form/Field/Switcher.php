<?php
/**
 * Switcher.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/20.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Form\Field;


use BaiSam\UI\Form\Field;
use Closure;

class Switcher extends Field
{
    protected $on = ['value' => 1, 'label' => 'ON', 'color' => null];
    protected $off = ['value' => 0, 'label' => 'OFF', 'color' => null];

    /**
     * 事件
     * @var array
     */
    protected $events = [];

    public function onSwitch($value, $label = null, $color = null)
    {
        $this->on['value'] = $value;
        if (isset($label)) {
            $this->on['label'] = $label;
        }
        if (isset($color)) {
            $this->on['color'] = $color;
        }

        return $this;
    }

    public function offSwitch($value, $label = null, $color = null)
    {
        $this->off['value'] = $value;
        if (isset($label)) {
            $this->off['label'] = $label;
        }
        if (isset($color)) {
            $this->off['color'] = $color;
        }

        return $this;
    }

    /**
     * 客户端响应事件
     * @param string $event
     * @param string $callback
     * @return $this
     */
    public function on($event, $callback)
    {
        $this->events[$event] = $callback;

        return $this;
    }

    /**
     * 当点击按钮时客户端响应事件
     *
     * @param string $callback
     * @return $this
     */
    public function onChange($callback)
    {
        return $this->on('change', $callback);
    }

    protected function buildEvents()
    {
        $events = $this->events;

        foreach ($events as $name => $event) {
            if ($event instanceof Closure) {
                $events[$name] = call_user_func($event);
            }
        }

        return $events;
    }

    protected function variables()
    {
        return array_merge(parent::variables(), [
            'on'        => $this->on,
            'off'       => $this->off,
            'events'        => $this->buildEvents()
        ]);
    }

}