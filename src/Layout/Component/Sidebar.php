<?php
/**
 * Sidebar.php
 * BaiSam admin
 *
 * Created by realeff on 2018/05/25.
 * Copyright ©2018 Jiangxi baisam information technology co., LTD. All rights reserved.
 */

namespace BaiSam\UI\Layout\Component;

use BaiSam\UI\Element;
use Illuminate\Support\Facades\View;

/**
 * 侧边栏（左、右）
 *
 * @package BaiSam\UI\Layout\Component
 */
class Sidebar extends Element
{
    /**
     * Sidebar the name.
     *
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * View for sidebar to render.
     *
     * @var string
     */
    protected $view = 'ui::layouts.sidebar';

    /**
     * Sidebar constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->name = $name;
    }

    //TODO 是否给侧边栏内容增加标识

    /**
     * Prepend item to sidebar.
     *
     * @param mixed $item
     * @return $this
     */
    public function prepend($item)
    {
        array_unshift($this->items, $item);

        return $this;
    }

    /**
     * Append item to sidebar.
     *
     * @param mixed $item
     * @return $this
     */
    public function append($item)
    {
        $this->items[] = $item;

        return $this;
    }

    //TODO 侧边栏样式定义

    /**
     * Build the items.
     *
     * @return array
     */
    protected function buildItems()
    {
        return $this->items;
    }

    /**
     * Get the view variables of this sidebar.
     *
     * @return array
     */
    protected function variables()
    {
        return array_merge(parent::variables(), [
            'name'          => $this->name,
            'items'         => $this->buildItems()
        ]);
    }

    /**
     * Get view of this field.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(array $data)
    {
        if ('ui::layouts.sidebar' == $this->view) {
            if (View::exists($this->view .'-'. $this->name)) {
                $this->view = $this->view .'-'. $this->name;
            }
        }

        return view($this->view, $data);
    }

}