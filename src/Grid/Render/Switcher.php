<?php
/**
 * Switcher.php
 * BaiSam BaiSam
 *
 * Created by realeff on 2018/09/29.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */


namespace BaiSam\UI\Grid\Render;


use BaiSam\UI\Grid\Render as AbstractRender;

class Switcher extends AbstractRender
{
    protected $on = ['value' => 1, 'label' => 'ON', 'color' => null];
    protected $off = ['value' => 0, 'label' => 'OFF', 'color' => null];

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

    public function render($value, $row, $builder)
    {
        // 调用模板输出 ui::grid.switcher
        $data = [
            //'id' => $builder->getId() .'_row_' . $row->getMajorKey() . '_' . $this->name,
            'name' => $this->name,
            'value' => $value,
            'on' => $this->on,
            'off' => $this->off
        ];

        return view('ui::grid.switcher', $data);
    }

}