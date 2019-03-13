<?php
/**
 * ButtonAction.php
 * BaiSam admin
 *
 * Created by realeff on 2018/10/28.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Actions;


use Closure;
use BaiSam\UI\Grid\Action;
use BaiSam\UI\UIRepository;
use BaiSam\UI\Form\Field\Button;
use BaiSam\UI\Grid\Traits\ActionRender;
use BaiSam\UI\Grid\Row;
use BaiSam\UI\Grid\Builder;

class ButtonAction extends Button implements Action
{
    use ActionRender;

    /**
     * @var string
     */
    protected $color = UIRepository::STYLE_DEFAULT;

    /**
     * @var string
     */
    protected $type = 'button';

    public function needSelectRow()
    {
        return false;
    }

    /**
     * @return string
     */
    protected function formatAttributes()
    {
        // 支持给Row添加事件
        $events = [];
        if (isset($this->params) && count($this->params) == 3
            && $this->params[1] instanceof Row
            && $this->params[2] instanceof Builder) {
            foreach ($this->events as $name => $event) {
                if ($event instanceof Closure) {
                    $event = call_user_func_array($event, $this->params);
                }

                if (empty($event)) {
                    continue;
                }

                $events[] = 'on'.$name.'="javascript:'.str_replace('"', '\\"', $event).';"';
            }
        }

        return parent::formatAttributes() . ' ' . implode(' ', $events);
    }

    /**
     * @return array
     */
    protected function buildEvents()
    {
        // 支持给Grid添加事件
        $events = [];
        if (isset($this->params) && is_scalar($this->params[0])) {
            return $events;
        }

        foreach ($this->events as $name => $event) {
            if ($event instanceof Closure) {
                $events[$name] = call_user_func_array($event, $this->params);
            }
            else {
                $events[$name] = (string)$event;
            }
        }

        return $events;
    }
}